<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;  

class SessionController extends Controller
{
    public function session() {
        echo '<pre>';
        print_r(Session::all());
    }
}
