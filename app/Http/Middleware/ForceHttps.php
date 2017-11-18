<?php
namespace App\Http\Middleware;

use Closure;
use Request;

class ForceHttps
{
    public function handle($request, Closure $next)
    {
        if (!app()->environment('local')) {
            // for Proxies
            Request::setTrustedProxies([$request->getClientIp()]);
            if (!$request->isSecure()) {
                return redirect()->secure($request->getRequestUri());
            }
        }
        return $next($request);
    }
}