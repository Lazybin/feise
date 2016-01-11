<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/test1",
 *     basePath="http://localhost/feise/public"
 * )
 */
class TestTwoController extends Controller
{
    //
    /**
     *
     * @SWG\Api(
     *   path="/test1/qqq",
     *   description="Operations about pets",
     *   @SWG\Operation(
     *     method="GET", summary="Find pet by ID", notes="Returns a pet based on ID",
     *     type="Pet", nickname="getPetById",
     *     @SWG\ResponseMessage(code=404, message="Pet not found"),
     *     @SWG\Parameter(
     *          name="petId",
     *          description="ID of pet that needs to be fetched",
     *          paramType="path",
     *          required=true,
     *          allowMultiple=false,
     *          type="string"
     *      )
     *   )
     * )
     */
    public function index(){
        echo 1111;
    }

    /**
     *
     * @SWG\Api(
     *   path="/test1/store",
     *   description="Operations about pets",
     *   @SWG\Operation(
     *     method="GET", summary="Find pet by ID", notes="Returns a pet based on ID",
     *     type="Pet", nickname="getPetById",
     *     @SWG\ResponseMessage(code=404, message="Pet not found"),
     *     @SWG\Parameter(
     *          name="petId",
     *          description="ID of pet that needs to be fetched",
     *          paramType="path",
     *          required=true,
     *          allowMultiple=false,
     *          type="string"
     *      )
     *   )
     * )
     */
    public function store(){
        echo 2222;
    }
}
