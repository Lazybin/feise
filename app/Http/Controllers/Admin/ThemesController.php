<?php

namespace App\Http\Controllers\Admin;

use App\Model\Themes;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ThemesController extends Controller
{
    public function show()
    {
        return view('admin.themes.index');
    }

    public function index(Request $request)
    {
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);


        $themes=Themes::skip($start)->take($length)->orderBy('id','desc');

        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval(Themes::count()),
            "recordsFiltered" => intval(Themes::count()),
            "data"            => $themes->get()->toArray()
        ));
    }
}
