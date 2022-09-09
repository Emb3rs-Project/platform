<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Inertia\Inertia;
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

    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        if (!app()->environment(['local', 'testing']) && in_array($response->status(), [500, 503, 404, 403, 419])) {
            switch ($response->status()) {
                case 503:
                    $title = '503: Service Unavailable';
                    $message = 'Sorry, we are doing some maintenance. Please check back soon.';
                    break;
                case 500:
                    $title = '500: Server Error';
                    $message = 'Whoops, something went wrong on our servers.';
                    break;
                case 404:
                    $title = '404: Page Not Found';
                    $message = 'Sorry, the page you are looking for could not be found.';
                    break;
                case 403:
                    $title = '403: Forbidden';
                    $message = 'Sorry, you are forbidden from accessing this page.';
                    break;
                case 419:
                    $title = '419: Page expired';
                    $message = 'The page expired, please try again.';
                    break;
                default:
                    $title = 'General Error';
                    $message = 'General error, please try again.';
                    break;
            }
            return back()->withErrors([
                'title' => $title,
                'text'  => $message,
                'type'  => 'danger'
            ]);
        }

        return $response;
    }
}
