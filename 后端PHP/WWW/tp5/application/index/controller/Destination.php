<?php
namespace app\index\controller;
use app\index\model\DestinationOrderComment as DestinationOrderCommentModel;
use app\index\model\Destination as DestinationModel;
use app\index\model\Price as PriceModel;
use think\Request; 
class Destination
{
  public function add(Request $request)
  { 
    $name = Request::instance()->post('name');
    if(!$name){
      return 'options';
    }
    $location = Request::instance()->post('location');
    $type = Request::instance()->post('type');
    $normalPrice = Request::instance()->post('normalPrice');
    $specialPrice = Request::instance()->post('specialPrice');
    $groupValue = Request::instance()->post('groupValue');
    $companyValue = Request::instance()->post('companyValue');
    $guideValue = Request::instance()->post('guideValue');
    $groupPrice = Request::instance()->post('groupPrice');
    $companyPrice = Request::instance()->post('companyPrice');
    $guidePrice = Request::instance()->post('guidePrice');
    $description = Request::instance()->post('description');
    $days = Request::instance()->post('days');
    $aggregateLocation = Request::instance()->post('aggregateLocation');
    $destination = new DestinationModel();
    $destination ->name = (string)$name;
    $destination ->location = (string)$location;
    $destination ->type =  (int)$type;
    $destination ->group = (int)$groupValue;
    $destination ->company = (int)$companyValue;
    $destination ->guide = (int)$guideValue;
    $destination ->hot = 69;
    $destination ->description = $description;
    $destination ->days = (int)$days;
    if($destination->save()){
      $price = new PriceModel();
      $id = $destination->id;
      $price ->id = $id;
      $price ->normalPrice = $normalPrice;
      $price ->specialPrice = $specialPrice;
      $price ->aggregateLocation = $aggregateLocation;
      if($groupPrice){
        $price ->groupPrice = $groupPrice;
      }else{
        $price ->groupPrice = null;
      }
      if($companyPrice){
        $price ->companyPrice = $companyPrice;
      }else{
        $price ->companyPrice = null;
      }
      if($guidePrice){
        $price ->guidePrice = $guidePrice;
      }else{
        $price ->guidePrice = null;
      }
      if($price->save()){
        return json_encode(
          array(
            'status' => 1,
            'message' =>  "success"
          )
        );
      }else{
        return json_encode(
          array(
            'status' => 0,
            'message' =>  "价格保存失败"
          )
        );
      }
      
    }else{
      return json_encode(
        array(
          'status' => 0,
          'message' =>  "基本信息保存失败"
        )
      );
    };
  }
  public function all()
  {
    $destination = DestinationModel::all();
    $price = PriceModel::all();
    return json_encode(
      array(
        'status' => 1,
        'data' =>  array(
            'destinations' => $destination,
            'price' => $price,
            'destinationsCount' => count($destination),
            'priceCount' => count($price)
          )
      )
    ); 
  }
  public function upData()
  {
    $id = Request::instance()->post('id');
    if(!$id){
      return 'options';
    }
    $destination = DestinationModel::get($id);
    $name = Request::instance()->post('name');
    $location = Request::instance()->post('location');
    $type = Request::instance()->post('type');
    $normalPrice = Request::instance()->post('normalPrice');
    $specialPrice = Request::instance()->post('specialPrice');
    $groupValue = Request::instance()->post('groupValue');
    $companyValue = Request::instance()->post('companyValue');
    $guideValue = Request::instance()->post('guideValue');
    $groupPrice = Request::instance()->post('groupPrice');
    $companyPrice = Request::instance()->post('companyPrice');
    $guidePrice = Request::instance()->post('guidePrice');
    $description = Request::instance()->post('description');
    $days = Request::instance()->post('days');
    $aggregateLocation = Request::instance()->post('aggregateLocation');
    $destination ->name = (string)$name;
    $destination ->location = (string)$location;
    $destination ->type =  (int)$type;
    $destination ->group = (int)$groupValue;
    $destination ->guide = (int)$guideValue;
    $destination ->company = (int)$companyValue;
    $destination ->days = (int)$days;
    $price = PriceModel::getById($id);
    $price ->normalPrice = $normalPrice;
    $price ->specialPrice = $specialPrice;
    if($aggregateLocation){
      $price ->aggregateLocation = $aggregateLocation;
    }else{
      $price ->aggregateLocation = null;
    }
    if($groupPrice){
      $price ->groupPrice = $groupPrice;
    }else{
      $price ->groupPrice = null;
    }
    if($companyPrice){
      $price ->companyPrice = $companyPrice;
    }else{
      $price ->companyPrice = null;
    }
    if($guidePrice){
      $price ->guidePrice = $guidePrice;
    }else{
      $price ->guidePrice = null;
    }
    try {
      $destination->save();
      $price->save();
      return json_encode(
        array(
          'status' => 1
        )
      ); 
    } catch (\Throwable $th) {
      return json_encode(
        array(
          'status' => 0
        )
      ); 
    }

    
  }
  public function single()
  { 
    $id = Request::instance()->get('id');
    if($id){
      $destination = DestinationModel::get($id);
      $price = PriceModel::getByid($id);
      $comment = DestinationOrderCommentModel::all(['destinationId'=>$id]);
      return json_encode(
        array(
          'status' => 1,
          'data' =>  array(
              'destinations' => $destination,
              'price' => $price,
              'comment' => $comment
            )
        )
      ); 
    }else{
      return 'options';
    }
  }
  public function test()
  {
    return 1;
  }
}
?>