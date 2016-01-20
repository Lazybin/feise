<?php

namespace App\Http\Controllers\Admin;

use App\Model\Subject;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
    public function show()
    {
        return view('admin.subjects.index');
    }

    public function index(Request $request)
    {
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);


        $subjects=Subject::skip($start)->take($length)->orderBy('id','desc');

        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval(Subject::count()),
            "recordsFiltered" => intval(Subject::count()),
            "data"            => $subjects->get()->toArray()
        ));
    }
}
