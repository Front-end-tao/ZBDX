<?php
namespace app\index\controller;
use app\index\model\DestinationOrderComment as DestinationOrderCommentModel;
use app\index\model\DestinationOrder as DestinationOrderModel;
use app\index\model\Destination as DestinationModel;

use think\Request; 
class Destinationordercomment
{
  public function add()
  {
    $orderId = Request::instance()->post('orderId');
    if(!$orderId){
      return 'options';
    }
    $destinationId = Request::instance()->post('destinationID');
    $comment = Request::instance()->post('comment');
    $grade = Request::instance()->post('grades');
    $userMail = Request::instance()->post('userMail');
    $orderComment = new DestinationOrderCommentModel();
    $orderComment ->orderId = (int)$orderId;
    $orderComment ->destinationId = (int)$destinationId;
    $orderComment ->comment = (string)$comment;
    $orderComment ->grade = (int)$grade;
    $orderComment ->userMail = (string)$userMail;
    $DestinationOrder = DestinationOrderModel::get($orderId);
    if($grade > 2){
      $Destination = DestinationModel::get($destinationId);
      $hot = $Destination->hot;
      $hot++;
      $Destination ->hot = (int)$hot;
      $Destination ->save();
    }else{
      $Destination = DestinationModel::get($destinationId);
      $hot = $Destination->hot;
      $hot--;
      $Destination ->hot = (int)$hot;
      $Destination ->save();
    }
    $DestinationOrder ->commentStatus = 1;
    if($orderComment ->save()){
      $DestinationOrder->save();
      return json_encode(
        array(
          'status' => 1,
          'message' =>  "success"
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
  public function getByDestination()
  {
    $destinationId = Request::instance()->get('des$destinationId');
    if(!$destinationId){
      return 'options';
    }
    return $destinationId;
  }
}
?>