<?php

namespace MicroweberPackages\User\Http\Controllers;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\App\Http\Middleware\SameSiteRefererMiddleware;

class UserLogoutController extends Controller
{
    public $middleware = [
        [
            'middleware' => 'xss',
            'options' => []
        ]
    ];

    public function __construct()
    {
        event_trigger('mw.init');
    }

    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ref = $request->headers->get('referer');

        $sameSite = app()->make(SameSiteRefererMiddleware::class);
        $isSameSite = $sameSite->isSameSite($ref);

        if ($isSameSite) {
            return logout($request->all());
        }

        return view('user::logout.index');
    }

    public function submit(Request $request)
    {
        Auth::logout();

        $url = site_url();
        $redirect = $request->post('redirect_to', false);
        if ($redirect) {
            $url = $redirect;
        }

        return app()->url_manager->redirect($url);
    }

}
