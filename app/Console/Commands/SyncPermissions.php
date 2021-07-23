<?php

namespace App\Console\Commands;

use App\EmbersPermissionable;
use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync permissions from Actions to the Database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $actionNamespaces = $this->getActionNamespaces();

        DB::transaction(function () use ($actionNamespaces) {
            foreach ($actionNamespaces as $actionNamespace) {
                $instance = app($actionNamespace);

                Permission::updateOrCreate([
                    'action' => $instance->getActionName(),
                ], [
                    'friendly_id' => Str::uuid()->toString(),
                    'friendly_name' => $instance->getFriendlyActionName(),
                    'group' => $instance->getGroupName()
                ]);
            }
        });
    }

    /**
     * All the available Action namespaces.
     *
     * @return array
     */
    public function getActionNamespaces(): array
    {
        $composer = require base_path('/vendor/autoload.php');

        $classes = array_keys($composer->getClassMap());

        $actionNamespaces = [];

        foreach ($classes as $class) {
            if (!Str::startsWith($class, 'App\\Actions\\')) {
                continue;
            }

            $traits = class_uses_recursive($class);

            if (!Arr::exists($traits, EmbersPermissionable::class)) {
                continue;
            }

            $actionNamespace = app($class)->getActionName();

            array_push($actionNamespaces, $actionNamespace);
        }

        return $actionNamespaces;
    }
}
