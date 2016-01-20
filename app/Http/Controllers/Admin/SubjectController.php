<?php

namespace App\Http\Controllers\Admin;

use App\Model\Subject;
use App\Model\SubjectThemes;
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

    public function store(Request $request)
    {
        $params=$request->all();

        if ($request->hasFile('coverImage'))
        {
            $file = $request->file('coverImage');
            $fileName=md5(uniqid()).'.'.$file->getClientOriginalExtension();
            $file->move(base_path().'/public/upload',$fileName);


            $params['cover']='/upload/'.$fileName;
        }
        unset($params['coverImage']);

        $chooseThemes=$params['chooseThemes'];
        unset($params['chooseThemes']);

        $subject=Subject::create($params);

        $len=strlen($chooseThemes);
        if($len>0){
            $chooseThemes=substr($chooseThemes,0,$len-1);
            $chooseThemes=explode(',',$chooseThemes);
            foreach($chooseThemes as $i){
                $subjectThemes=new SubjectThemes();
                $subjectThemes->subject_id=$subject->id;
                $subjectThemes->theme_id=$i;
                $subjectThemes->save();
            }
        }


        return redirect()->action('Admin\SubjectController@show');
    }

    public function detail($id)
    {
        $goods=Subject::find($id);
        $ret['meta']['code']=1;
        $ret['meta']['data']=$goods;
        echo json_encode($ret);
    }

    public function update(Request $request,$id)
    {
        $params=$request->all();
        $subject=Subject::find($id);
        if($subject!=null){
            if ($request->hasFile('coverImage'))
            {
                $file = $request->file('coverImage');
                $fileName=md5(uniqid()).'.'.$file->getClientOriginalExtension();
                $file->move(base_path().'/public/upload',$fileName);


                $params['cover']='/upload/'.$fileName;
            }
            unset($params['coverImage']);


            unset($params['_token']);



            $chooseThemes=$params['chooseThemes'];
            unset($params['chooseThemes']);

            foreach($params as $n=>$p){
                $subject->$n=$p;
            }
            $subject->save();


            SubjectThemes::where('subject_id',$subject->id)->delete();
            $len=strlen($chooseThemes);
            if($len>0){
                $chooseThemes=substr($chooseThemes,0,$len-1);
                $chooseThemes=explode(',',$chooseThemes);
                foreach($chooseThemes as $i){
                    $subjectThemes=new SubjectThemes();
                    $subjectThemes->subject_id=$subject->id;
                    $subjectThemes->theme_id=$i;
                    $subjectThemes->save();
                }
            }
        }
        return redirect()->action('Admin\SubjectController@show');
    }

    public function delete($id)
    {
        Subject::find($id)->delete();
        $ret['meta']['code']=1;
        echo json_encode($ret);
    }
}
