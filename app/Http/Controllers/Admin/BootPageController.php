<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BootPageController extends Controller
{
    //
    public function show()
    {
        return view('admin.setting.boot_page');
    }

    public function store(Request $request)
    {
        $file = $request->file('input-id');

        if ($file->isValid())
        {
            $file->move(base_path().'/public/upload',time().'.'.$file->getClientOriginalExtension());
        }
    }
}
