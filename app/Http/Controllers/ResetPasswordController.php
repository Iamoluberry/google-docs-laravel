<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{

    public function reset(Request $request){
        $request->validate([
           'token' => 'required',
           'email' => 'required|email',
           'password' => 'required|min:6|confirmed',
           'password_confirmation' => 'required|min:6'
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(10),
                ])->save();
            });

        return $status === Password::RESET_LINK_SENT ?
            response()->json(['status' => __($status)], 200) :
            response()->json(['status' => __($status)], 400);
    }
}
