<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class HandleReporterConnectorRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $namespace = $request->header('X-EMBERS-NAMESPACE');
        $key = $request->header('X-EMBERS-KEY');

        if (is_null($namespace) || is_null($key)) throw new AuthenticationException();

        $uuid = Uuid::uuid5(config('app.reporter_key'), $namespace);

        if ($uuid->toString() !== $key) throw new AuthenticationException();

        return $next($request);
    }
}
