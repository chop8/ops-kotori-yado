<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    private function getPassword()
    {
        return env('EDIT_PASSWORD', 'pass123'); // Default for fallback
    }

    public function unlock(Request $request)
    {
        $pass = $request->input('pass');
        $returnUrl = $request->input('return_url', '/');

        // パスワードが空の場合は元のページにリダイレクト
        if (empty($pass)) {
            return Redirect::to($returnUrl);
        }

        if ($pass === $this->getPassword()) {
            Cookie::queue('is_login', '1', 0);
            Cookie::queue('pass', $pass, 60 * 24 * 30);
            return Redirect::to($returnUrl);
        }

        return view('reception.error');
    }

    public function lock(Request $request)
    {
        $returnUrl = $request->input('return_url', '/');

        $response = Redirect::to($returnUrl);

        $response->withCookie(Cookie::forget('is_login'));
        $response->withCookie(Cookie::forget('pass'));

        return $response;
    }
}
