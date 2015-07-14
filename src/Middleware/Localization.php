<?php namespace LaurentEsc\Localization\Middleware;

use Illuminate\Http\RedirectResponse;
use Closure;
use LaurentEsc\Localization\Facades\Localize;
use LaurentEsc\Localization\Facades\Router;

class Localization
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Localize::shouldRedirect()) {
            return new RedirectResponse(Router::getRedirectURL(), 302, ['Vary', 'Accept-Language']);
        }

        return $next($request);
    }

}
