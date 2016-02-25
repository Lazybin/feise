<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BaseResponse;
use App\Model\Order;
use App\Model\Refund;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/refunds",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class RefundsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     *
     * @SWG\Api(
     *   path="/refunds",
     *   description="退款（20160225更新）",
     *   @SWG\Operation(
     *     method="POST", summary="待发货申请退款", notes="申请退款",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="refunds_info",
     *         description="提交的退款信息",
     *         paramType="body",
     *         required=true,
     *         type="Refund1"
     *     )
     *   )
     * )
     */
    public function store(Request $request)
    {
        //order_id,refund_reason,refund_explain,pic1,pic2,pic3
        $response=new BaseResponse();
        $content = json_decode($request->getContent(false));

        $this->dealImage($content);
        $content->type=1;

        $refund=Refund::create((array)$content);
        $refund=Refund::create((array)$content);
        if(!$this->dealOrder($content,$refund)){
            return (new BaseResponse(BaseResponse::CODE_ERROR_BUSINESS, '订单状态错误'))->toJson();
        }else{
            return $response->toJson();
        }
    }

    /**
     *
     * @SWG\Api(
     *   path="/refunds/only_refund_money",
     *   @SWG\Operation(
     *     method="POST", summary="已发货仅退款", notes="申请退款",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="refunds_info",
     *         description="提交的退款信息",
     *         paramType="body",
     *         required=true,
     *         type="Refund2"
     *     )
     *   )
     * )
     */
    public function onlyRefundMoney(Request $request){
        $response=new BaseResponse();
        $content = json_decode($request->getContent(false));

        $this->dealImage($content);

        $content->type=3;

        $refund=Refund::create((array)$content);
        if(!$this->dealOrder($content,$refund)){
            return (new BaseResponse(BaseResponse::CODE_ERROR_BUSINESS, '订单状态错误'))->toJson();
        }else{
            return $response->toJson();
        }

    }

    /**
     *
     * @SWG\Api(
     *   path="/refunds/both_refund",
     *   @SWG\Operation(
     *     method="POST", summary="已发货退货退款", notes="申请退款",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="refunds_info",
     *         description="提交的退款信息",
     *         paramType="body",
     *         required=true,
     *         type="Refund1"
     *     )
     *   )
     * )
     */
    public function bothRefund(Request $request){
        $response=new BaseResponse();
        $content = json_decode($request->getContent(false));

        $this->dealImage($content);

        $content->type=2;

        $refund=Refund::create((array)$content);
        if(!$this->dealOrder($content,$refund)){
            return (new BaseResponse(BaseResponse::CODE_ERROR_BUSINESS, '订单状态错误'))->toJson();
        }else{
            return $response->toJson();
        }

    }

    private function dealOrder($content,$refund){
        $order=Order::find($content->order_id);
        switch($order->status){
            case 1:
                $order->status=5;
                $order->save();
                break;
            case 3:
                $order->status=6;
                $order->save();
                break;
            default :
                $refund->delete();
                return false;
        }
        return true;
    }


    private function dealImage(&$content){
        if($content->pic1!=''){
            $content->pic1=$this->uploadFile($content->pic1);
        }

        if($content->pic2!=''){
            $content->pic2=$this->uploadFile($content->pic2);
        }

        if($content->pic3!=''){
            $content->pic3=$this->uploadFile($content->pic3);
        }
    }

    private function uploadFile($content)
    {
        $pic1=base_path().'/public/upload/'.md5(uniqid()).'.jpg';
        file_put_contents($pic1,base64_decode($content));
        $imageInfo = getimagesize($pic1);
        if(strpos($imageInfo['mime'], 'image') === false) {
            return '';
        }
        $fileSize = filesize($pic1);
        if($fileSize > UploadedFile::getMaxFilesize()) {
            unlink($pic1);
            return '';
        }
        return $pic1;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
    }
}
