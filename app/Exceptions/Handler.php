<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $localEnvironment = app()->environment('local');

        $this->reportable(function (Throwable $e) {
            //
        });

        // TODO: check inertia handling on this occasion and uncomment/delete as necessary
        // $this->renderable(function (NotFoundHttpException $e, Request $request) use ($localEnvironment) {
        //     if (!$localEnvironment) {
        //         if ($request->wantsJson()) {
        //             return response()->json(['message' => 'The request resource could not be found'], 404);
        //         }

        //         return parent::render($request, $e);
        //     }
        //     return parent::render($request, $e);
        // });
    }
}
