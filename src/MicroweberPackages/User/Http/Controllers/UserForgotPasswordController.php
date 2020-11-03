<?php

namespace MicroweberPackages\User\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserForgotPasswordController extends Controller
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
       //$this->middleware('throttle:10,1');
    }


    public function showForgotForm()
    {
        return view('user::auth.forgot-password');
    }

    public function send(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request)
    {
        return view('user::auth.reset-password', [
            'email' => $request->email,
            'token' => $request->token,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:1|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                $user->setRememberToken(Str::random(60));

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('user.login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}