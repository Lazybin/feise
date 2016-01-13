<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class TestController extends Controller
{

    public function index(){

        $user = Auth::user();
        var_dump($user);
        echo 1111;
    }
}
