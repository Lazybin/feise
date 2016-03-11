<?php

namespace App\Http\Controllers\Admin;

use App\Model\Authorization;
use App\Model\Role;
use App\Model\RoleAuthorization;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $data['authorizations']=Authorization::all()->toArray();
        return view('admin.permission.role_index',$data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);

        $role=Role::skip($start)->take($length)->orderBy('id','desc');

        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval(Role::count()),
            "recordsFiltered" => intval(Role::count()),
            "data"            => $role->get()->toArray()
        ));
    }

    public function detail($id)
    {
        $roles=Role::find($id)->toArray();


        $roles['authorizations']=RoleAuthorization::where('role_id','=',$id)->get()->toArray();

        $ret['meta']['code']=1;
        $ret['meta']['data']=$roles;
        echo json_encode($ret);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params=$request->all();
        $role=new Role();
        $role->name=$params['name'];
        $role->save();
        foreach($params['authorizations'] as $v){
            $roleAuthorizations=new RoleAuthorization();
            $roleAuthorizations->role_id=$role->id;
            $roleAuthorizations->authorization_id=$v;
            $roleAuthorizations->save();
        }
        return redirect()->action('Admin\RoleController@show');
    }





    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $params=$request->all();
        $role=Role::find($id);
        $role->name=$params['name'];
        $role->save();
        RoleAuthorization::where('role_id','=',$id)->delete();
        foreach($params['authorizations'] as $v){
            $roleAuthorizations=new RoleAuthorization();
            $roleAuthorizations->role_id=$role->id;
            $roleAuthorizations->authorization_id=$v;
            $roleAuthorizations->save();
        }
        return redirect()->action('Admin\RoleController@show');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Role::find($id)->delete();
        $ret['meta']['code']=1;
        echo json_encode($ret);
    }
}
