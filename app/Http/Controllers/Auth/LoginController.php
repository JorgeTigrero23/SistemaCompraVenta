<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $rules = [
            'username' => 'required|string',
            'password' => 'required|string',
        ];

        $message = [
            'username.required' => 'El campo nombre de usuario es requerido.',
            'password.required' => 'El campo password es requerido.',
        ];

        $this->validate($request, $rules, $message);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password]))
        {
            return redirect()->route('main');
        }

        return back()->withErrors(['username' => trans('Estas credenciales no coinciden con nuestros registros.')])
                        ->withInput(request(['username']));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }
}
