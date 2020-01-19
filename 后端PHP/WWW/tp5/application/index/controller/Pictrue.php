<?php
namespace app\index\controller;
use think\Controller; 
use think\Request; 
use app\index\model\Destination as DestinationModel;
class Pictrue extends Controller 
{ 
  //文件上传提交 

  public function upLoad() 
  { 
    //获取表单上传文件 
    if(request()->isPost()){
      if($_FILES){
        $id = Request::instance()->post('id');
        $path = 'C:\phpstudy_pro\WWW\tp5\public\uploads\pic'.$id.'.jpg';
        $tmp = $_FILES['upfile']['tmp_name'];
        if(move_uploaded_file($tmp,$path)){
          $dataPath = 'tp5\public\uploads\pic'.$id.'.jpg';
          $destination = DestinationModel::get($id);
          $destination -> picture = $dataPath;
          $destination->save();
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
              'message' => 'move_uploaded_file is bad'
            )
          ); 
        }
      } else{
        return json_encode(
          array(
            'status' => 0,
            'message' => '$_FILES is bad'
          )
        ); 
      }
    }else{
      return 'options';
    }
    

    // //移动到框架应用根目录/public/uploads/ 目录下 

    // $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'); 

    // if ($info) { 

    //   $this->success('文件上传成功'); 

    //   echo $info->getFilename(); 

    // } else { 

    //   //上传失败获取错误信息 

    //   $this->error($file->getError()); 

    // } 

  } 

}
?>

