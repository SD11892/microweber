<?php

namespace MicroweberPackages\App\Http\Middleware;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Cookie;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];




    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, \Closure $next)
    {

        try {
            return parent::handle($request, $next);
        }  catch (TokenMismatchException $e) {
             return abort(403, 'Unauthorized action. The CSRF token is invalid.');
         } catch (DecryptException $e) {
           return abort(403, 'Unauthorized action. The CSRF token payload is invalid or not encrypted.');
        }

     }



    /**
     * Add the CSRF token to the response cookies.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function addCookieToResponse($request, $response)
    {
        if (!is_object($response)) {
            return;
        }

        return parent::addCookieToResponse($request, $response);
    }


    public function forceAddAddXsrfTokenCookie($request, $response)
    {
        return $this->addCookieToResponse($request, $response);
    }



}
