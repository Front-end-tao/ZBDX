<?php
namespace app\index\controller;
use app\index\model\DestinationOrderComment as DestinationOrderCommentModel;
use app\index\model\DestinationOrder as DestinationOrderModel;
use think\Request; 
class Destinationorder
{
  public function add(Request $request)
  {
    $orderMail = Request::instance()->post('orderMail');
    if(!$orderMail){
      return 'options';
    }
    $orderCreateTime = Request::instance()->post('orderCreateTime');
    $orderTime = Request::instance()->post('orderTime');
    $group = Request::instance()->post('group');
    $company = Request::instance()->post('company');
    $guideDays = Request::instance()->post('guideDays');
    $orderMoney = Request::instance()->post('orderMoney');
    $destinationID = Request::instance()->post('destinationID');
    $payStatus = Request::instance()->post('payStatus');
    $orderNameString = Request::instance()->post('orderNameString');
    $orderIdString = Request::instance()->post('orderIdString');
    $orderCountryString = Request::instance()->post('orderCountryString');
    $orderSexString = Request::instance()->post('orderSexString');
    $orderTelString = Request::instance()->post('orderTelString');
    $DestinationOrder = new DestinationOrderModel();
    $DestinationOrder ->orderMail = $orderMail;
    $DestinationOrder ->orderCreateTime = (int)$orderCreateTime;
    $DestinationOrder ->orderTime = (int)$orderTime;
    $DestinationOrder ->group = (int)$group;
    $DestinationOrder ->company = (int)$company;
    $DestinationOrder ->guideDays = (int)$guideDays;
    $DestinationOrder ->orderMoney = (int)$orderMoney;
    $DestinationOrder ->destinationID = (int)$destinationID;
    $DestinationOrder ->payStatus = (int)$payStatus;
    $DestinationOrder ->orderNameString = $orderNameString;
    $DestinationOrder ->orderIdString = $orderIdString;
    $DestinationOrder ->orderCountryString = $orderCountryString;
    $DestinationOrder ->orderSexString = $orderSexString;
    $DestinationOrder ->orderTelString = $orderTelString;
    $DestinationOrder ->payWatch = 0;
    $DestinationOrder ->commentStatus = 0;
    if($DestinationOrder->save()){
      return json_encode(
        array(
          'status' => 1,
          'message' =>  "success",
          'orderId' => $DestinationOrder->orderId
        )
      );
    }else {
      return json_encode(
        array(
          'status' => 0,
          'message' =>  "error"
        )
      );
    }
  }
  public function pay()
  {
    $orderId = Request::instance()->get('orderId');
    if(!$orderId){
      return 'options';
    }
    $DestinationOrder = DestinationOrderModel::get($orderId);
    if(!$DestinationOrder){
      return json_encode(
        array(
          'status' => 0,
          'message' => '订单号无效'
        )
      ); 
    }
    $DestinationOrder ->payStatus = 1;
    if($DestinationOrder->save()){
      return json_encode(
        array(
          'status' => 1,
          'message' => 'success'
        )
      ); 
    }else{
      return json_encode(
        array(
          'status' => 0,
          'message' => '订单号有效，修改失败。'
        )
      ); 
    }
  }
  public function stopLastWatch()
  { 
    $orderId = Request::instance()->get('orderId');
    if(!$orderId){
      return 'options';
    }
    $DestinationOrder = DestinationOrderModel::all();
    foreach ($DestinationOrder as $key => $order) {
      $order->payWatch = 1;
      $order->save();
    }
    sleep(3);
    foreach ($DestinationOrder as $key => $order) {
      $order->payWatch = 0;
      $order->save();
    }
    return json_encode(
      array(
        'status' =>1
      )
    ); 
  }
  public function watchPay()
  {
    ignore_user_abort(TRUE);
    set_time_limit(0);
    $count = 0;
    while (TRUE)
    {
      sleep(1);
      $count ++;
      if($count == 30){
        return json_encode(
          array(
            'status' => 0,
            'message' => 'out time'
          )
        ); 
      }
      $orderId = Request::instance()->get('orderId');
      $order = DestinationOrderModel::get($orderId);
      if($order->payStatus == 1){
        return json_encode(
          array(
            'status' => 1
          )
        );
      }
      if($order->payWatch == 1){
        return json_encode(
          array(
            'status' => 2,
            'message' => 'stoped'
          )
        );
      }
      
    } 
  }
  public function getOrders()
  { 
    $orderMail = Request::instance()->get('orderMail');
    if(!$orderMail){
      return json_encode(
        array(
          'status' => 0,
          'message' => 'options'
        )
      ); 
    }
    $Destinationorder = DestinationOrderModel::all(['orderMail'=>$orderMail]);
    return json_encode(
      array(
        'status' => 1,
        'DestinationOrders' => $Destinationorder,
        'Count' => count($Destinationorder)
      )
    ); 
  }
  public function all()
  {
    $Destinationorder = DestinationOrderModel::all();
    return json_encode(
      array(
        'status' => 1,
        'data' => $Destinationorder
      )
    ); 
  }
  public function del()
  {
    $DestinationorderID = Request::instance()->delete('destinationorderid');
    $order = DestinationOrderModel::get($DestinationorderID);
    $orderComment = DestinationOrderCommentModel::get($DestinationorderID);
    if ($order) {
      $order->delete();
      if($orderComment) {
        $orderComment->delete();
      }
      return json_encode(
        array(
          'status' => 1,
          'message' =>  "删除成功"
        )
      ); 
    } else {
      return json_encode(
        array(
          'status' => 0,
          'message' =>  "删除失败"
        )
      );
    }
  }
}
?>