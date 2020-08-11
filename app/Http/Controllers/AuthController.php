<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthModel;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller {

    private $authModel;

    public function __construct() {
        $this->authModel = new AuthModel();
    }

    public function login(Request $request) {

        $this->validate($request, [
            'login'     => 'required',
            'password'  => 'required|alphaNum|min:3',
        ]);

        $login    = filter_var($request->login, FILTER_SANITIZE_STRING);
        $password = filter_var($request->password, FILTER_SANITIZE_STRING);

        $userData = $this->authModel->getUser($login, md5($password));

        if (!$userData) {
            Session::flash('errorLogin', 'Invalid credentials! :( ');

            return redirect()->route('auth');
        }

        Session::put('sessionUser', [
            'id'         => $userData[0]->cod,
            'firstname'  => $userData[0]->nome,
            'email'      => $userData[0]->nome . '@empr.com.br',
            'isLoggedIn' => true
        ]);

        return redirect()->route('home');
    }

    public function logoff() {
        Session::forget('sessionUser');

        return $this->auth();
    }

    public function auth() {
        return view('user.auth');
    }
}
