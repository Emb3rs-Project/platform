<?php

namespace App\Helpers\Nova\Action;

use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Queue;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionEvent;
use Laravel\Nova\Actions\ActionMethod;
use Laravel\Nova\Actions\CallQueuedAction;
use Laravel\Nova\Actions\Transaction;
use Laravel\Nova\Exceptions\MissingActionHandlerException;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Nova;

class DispatchCustomAction
{
    /**
     * Dispatch the given action.
     *
     * @param \Laravel\Nova\Actions\Action $action
     * @param \Laravel\Nova\Fields\ActionFields $actionFields
     * @param $models
     * @param int $user_id
     * @throws MissingActionHandlerException
     * @throws \Throwable
     */
    public static function dispatchAction(Action $action, ActionFields $actionFields, $models, $user_id = 0)
    {
        $models = \Illuminate\Database\Eloquent\Collection::wrap($models);

        if ($models->isEmpty()) {
            return;
        }

        $method = ActionMethod::determine($action, $models->first());

        if (!method_exists($action, $method)) {
            throw MissingActionHandlerException::make($action, $method);
        }


        if ($action instanceof ShouldQueue) {
            self::queueForModels($action, $method, $actionFields, $models, $user_id);

            return;
        }

        self::forModels($action, $method, $actionFields, $models, $user_id);
    }

    /**
     * Dispatch the given action in the background.
     *
     * @param \Laravel\Nova\Actions\Action $action
     * @param string $method
     * @param $fields
     * @param \Illuminate\Support\Collection $models
     * @param $user_id
     * @return void
     * @throws \Throwable
     */
    protected static function queueForModels(Action $action, $method, ActionFields $fields, $models, $user_id)
    {
        return Transaction::run(function ($batchId) use ($action, $method, $fields, $models, $user_id) {
            if (!$action->withoutActionEvents) {
                self::createActionEvents($action, $batchId, $models, $user_id, 'waiting');
            }

            Queue::connection($action->connection)->pushOn(
                $action->queue,
                new CallQueuedAction(
                    $action,
                    $method,
                    $fields,
                    $models,
                    $batchId
                )
            );
        });
    }

    /**
     * Dispatch the given action.
     *
     * @param \Laravel\Nova\Actions\Action $action
     * @param string $method
     * @param $fields
     * @param \Illuminate\Support\Collection $models
     * @param $user_id
     * @return void
     * @throws \Throwable
     */
    protected static function forModels(Action $action, $method, ActionFields $fields, $models, $user_id)
    {
        return Transaction::run(function ($batchId) use ($action, $method, $fields, $models, $user_id) {
            if (!$action->withoutActionEvents) {
                self::createActionEvents($action, $batchId, $models, $user_id, 'running');
            }

            return $action->withBatchId($batchId)->{$method}($fields, $models);
        }, function ($batchId) {
            Nova::actionEvent()->markBatchAsFinished($batchId);
        });
    }

    /**
     * Create a new action events for models
     *
     * @param Action $action
     * @param $batchId
     * @param \Illuminate\Support\Collection $models
     * @param int $user_id
     * @param string $status
     */
    protected static function createActionEvents(Action $action, $batchId, $models, $user_id, $status = 'running')
    {
        $models = $models->map(function ($model) use ($action, $batchId, $status, $user_id) {
            return [
                'batch_id' => $batchId,
                'user_id' => $user_id,
                'name' => $action->name(),
                'actionable_type' => $model->getMorphClass(),
                'actionable_id' => $model->id,
                'target_type' => $model->getMorphClass(),
                'target_id' => $model->id,
                'model_type' => $model->getMorphClass(),
                'model_id' => $model->id,
                'fields' => 'a:0:{}',
                'status' => $status,
                'exception' => '',
                'original' => null,
                'changes' => null,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ];
        });

        $models->chunk(50)->each(function (Collection $models) {
            ActionEvent::insert($models->all());
        });
    }
}
