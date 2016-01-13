<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TestTwoController extends Controller
{

    public function index(){
        echo 1111;
    }


    public function store(){
        echo 2222;
    }
}
