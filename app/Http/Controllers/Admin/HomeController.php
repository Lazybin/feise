<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    //
    public function index()
    {
        return redirect()->action('Admin\HomeManageController@show');
     //   return view('admin.home.index');
    }
}
