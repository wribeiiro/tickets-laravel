<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Models\HomeModel;

class HomeController extends Controller {

    private $objectHome;

    public function __construct() {
        //$this->objectHome = new HomeModel();
    }

    public function index() {
        //$arrayData = $this->objectHome->paginate(15);

        return view('home.index');
    }
}
