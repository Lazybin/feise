<?php

namespace App\Http\Controllers;

use App\Model\Images;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UploadFileController extends Controller
{
    //
    public function upload(Request $request)
    {
        // 'images' refers to your file input name attribute
        $type=$request->input('type','uploadImages');
        if (empty($_FILES[$type])) {
            echo json_encode(['error'=>'没有发现要上传的文件']);
            // or you can throw an exception
            return; // terminate
        }

        // get the files posted
        $images = $_FILES[$type];




        // a flag to see if everything is ok
        $success = null;

        // file paths to store
        $paths= [];

        $initialPreview=[];

        $initialPreviewConfig=[];

        // get file names
        $filenames = $images['name'];

        // loop and process files
        for($i=0; $i < count($filenames); $i++){
            $ext = explode('.', basename($filenames[$i]));

            $save_name=md5(uniqid()) . "." . array_pop($ext);

            $target = base_path().DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR .'upload'.DIRECTORY_SEPARATOR . $save_name;
            $show_path=url('/').'/upload/'.$save_name;
            if(move_uploaded_file($images['tmp_name'][$i], $target)) {
                $paths[] = $show_path;
                $success = true;
            } else {
                $success = false;
                break;
            }
        }

        // check and process based on successful status
        if ($success === true) {
            foreach($paths as $p){
                $initialPreview[]="<img src='".$p."' class='file-preview-image' >";
                $image=new Images();
                $image->path=$p;
                $image->save();

                $config=(object)null;
                $config->url=url('/upload_file/delete');
                $config->key=$image->id;
                $initialPreviewConfig[]=$config;
            }

            $output = ['initialPreview' => $initialPreview,'initialPreviewConfig'=>$initialPreviewConfig];
        } elseif ($success === false) {
            $output = ['error'=>'上传文件出错'];
            // delete any uploaded files
            foreach ($paths as $file) {
                unlink($file);
            }
        } else {
            $output = ['error'=>'没有文件上传'];
        }

        // return a json encoded response for plugin to process successfully
        echo json_encode($output);

    }

    public function delete(Request $request)
    {
        $id=$request->input('key');
        //Images::find($id)->delete();
        $output = ['key'=>$id];
        echo json_encode($output);
    }
}
