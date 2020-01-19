<?php
namespace app\index\controller;
use app\index\model\User as UserModel;
use think\Request; 
class User
{   

  // 新增一条数据的方法
  // public function add()
  // { 
  //   $user = new UserModel();
  //   $user ->phoneNumber = 18406586090;
  //   $user ->nickName = 'model';
  //   $user ->password = 19971102;
  //   $user ->email = '1550229128@qq.com';
  //   try {
  //     if($user->save()){
  //       return 'success';
  //     }
  //   } catch (\Throwable $th) {
  //     return $th->getMessage();
  //   }
    // if($user->save()){
    //   return 'success';
    // }else{
    //   return 'error';
    // }
    // try {
    //   $user['phoneNumber'] = 18406586099;
    //   $user['nickName'] = 'model';
    //   $user['password'] = 19971102;
    //   $user['email'] = '1550229128@qq.com';
    //   if(UserModel::Create($user)) {
    //     return 'success';
    //   }else{
    //     return 'error';
    //   }
    // } } catch (Exception $e) {
    //   echo $e->getMessage();
    //   // die(); // 终止异常
    // }
  // }

  // // 批量新增数据
  // public function addList()
  // { 
  //   $user = new UserModel();
  //   $list = [
  //     ['phoneNumber' => 18406586092,'nickName' => 'model','password' => 19971102,'email' => '15501229128@qq.com'],
  //     ['phoneNumber' => 18406586093,'nickName' => 'model','password' => 19971102,'email' => '15501229128@qq.com'],
  //     ['phoneNumber' => 18406586094,'nickName' => 'model','password' => 19971102,'email' => '15501229128@qq.com'],
  //     ['phoneNumber' => 18406586095,'nickName' => 'model','password' => 19971102,'email' => '15501229128@qq.com']
  //   ];
  //   $user -> saveAll($list);
  //   if($user->saveAll($list)){
  //     return 'success';
  //   }else{
  //     return 'error';
  //   }
  // }

  // // 更新数据
  // public function update() { 
    
  //   // 第一种更新数据的方法
  //   // $user = UserModel::get(18406586092); 
  //   // $user->nickName = '更改之后的值';
  //   // $user->password = 1234567;
  //   // if($user->save()){
  //   //   return 'success';
  //   // }else{
  //   //   return 'file';
  //   // }

  //   // 第二种更细数据的方法
  //   // $user = UserModel::get(18406586092); 
  //   // $user->save(['nickName'=>'nickkkkkkkkkk']);

  //   // 批量修改数据
  //   // $user = new UserModel();
  //   // $list = [
  //   //   ['phoneNumber' => 18406586090, 'nickName' => 'nick1'],
  //   //   ['phoneNumber' => 18406586091, 'nickName' => 'nick2'],
  //   // ];
  //   // $user->saveAll($list);
  // }

  // //获取数据
  // public function read()
  // { 
  //   // 单个
  //   // $phoneNumber = 18406586090;
  //   // $user = UserModel::get($phoneNumber);
  //   // echo $user->nickName;

  //   $user = UserModel::getByEmail('1550229128@qq.com');
  //   echo $user;
  //   echo '------<br/>'; 



  //   // 多个
  //   // $list = UserModel::all(['email'=> '1550229128@qq.com']); 
  //   // foreach ($list as $user) {
  //   //  echo $user->nickName . '<br/>'; 
  //   //  echo $user->email . '<br/>'; 
  //   //  echo '----------------------------------<br/>'; 
  //   // }
  // }

  // //删除数据
  public function delete()
  {
    $user = UserModel::get(18406586090);
    if ($user) {
      $user->delete();
      echo "删除成功";
    } else {
      echo "找不到该数据";
    }
  }





  public function checkMail()
  {   
    $mail = Request::instance()->post('mail');
    $user = UserModel::get($mail);
    if (empty($user)) {
      return json_encode(
                array(
                    'status' => 1,
                    'message' => 'mail is not registered'
                )
              );
    } else {
      return json_encode(
                array(
                    'status' => 0,
                    'message' => 'mail is registered'
                )
              );
    }
  }
  public function add()
  {
    $mail = Request::instance()->post('mail');
    $userName = Request::instance()->post('userName');
    $password = Request::instance()->post('password');
    $user = new UserModel();
    $user ->mail = $mail;
    $user ->userName =  $userName;
    $user ->password = $password;
    try {
      if($user->save()){
        return json_encode(
          array(
              'status' => 1,
              'message' => 'register success'
          )
        );
      }
    } catch (\Throwable $th) {
      return json_encode(
        array(
            'status' => 0,
            'message' => $th->getMessage()
        )
      );
    }
  }
  public function login()
  {
    $mail = Request::instance()->get('mail');
    $password = Request::instance()->get('password');
    $user = UserModel::get($mail);
    if($user){
      if($user->password == $password){
        return json_encode(
          array(
              'status' => 1,
              'message' => 'login success',
              'data' => $user
          )
        );
      }else{
        return json_encode(
          array(
              'status' => 1,
              'message' => 'login fail',
              'data' => null
          )
        );
      }
    }else{
      return json_encode(
        array(
            'status' => 0,
            'message' => 'mail is not registered',
            'data' => null
        )
      );
    }
  }
  public function sendMail(){
    $mail = Request::instance()->get('mail');
    $code = rand('100000','999999');        //六位随机数
    $toemail= $mail;            //收件人的邮箱
    $to_name = 'tourismweb客户';                    //设置收件人信息，如邮件格式说明中的收件人
    $title = '来自tourismweb的验证码';    
    $content = "您好，欢迎使用tourismweb，您的验证码为：【{$code}】";


    $sendmail = 'lt971102@163.com';     //发件人邮箱
    $sendmailpswd = "liangtao123";              //客户端授权密码,而不是邮箱的登录密码，就是手机发送短信之后弹出来的一长串的密码
    $send_name = 'tourismweb';                // 设置发件人信息，如邮件格式说明中的发件人，
    $mail = new \PHPMailer\PHPMailer();      //实例化mail类
    $mail->isSMTP();                        // 使用SMTP服务
    $mail->CharSet = "utf8";                // 编码格式为utf8，不设置编码的话，中文会出现乱码
    $mail->Host = "SMTP.163.com";           // 发送方的SMTP服务器地址
    $mail->SMTPAuth = true;                 // 是否使用身份验证
    $mail->Username = $sendmail;            // 发件人地址
    $mail->Password = $sendmailpswd;        //客户端授权密码,而不是邮箱的登录密码！
    $mail->SMTPSecure = "ssl";              // 使用ssl协议方式
    $mail->Port = 465;                      //sina端口110或25） //qq  465 587
    $mail->setFrom($sendmail, $send_name);  // 设置发件人信息，如邮件格式说明中的发件人，
    $mail->addAddress($toemail, $to_name);  // 设置收件人信息，如邮件格式说明中的收件人，
    $mail->addReplyTo($sendmail, $send_name);// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
    $mail->Subject = $title;     // 邮件标题
    //$code=$code;
     session("code",$code);
    $mail->Body = $content;                 // 邮件正文
    //$mail->AltBody = "This is the plain text纯文本";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用
    if(!$mail->send()){
      //  return json(["code"=>400,"result"=>$mail->ErrorInfo,"data"=>'']);//返回数据格式自己定义的一个函数
       return json_encode(
        array(
            'status' => 0,
            'err' => $mail->ErrorInfo
        )
      );

    }else{
        return json_encode(
          array(
              'status' => 1,
              'mail' => Request::instance()->get('mail'),
              'code' => $code
          )
        );
    }
  }
  public function changePassword()
  { 
    $mail = Request::instance()->post('mail');
    $newPassword = Request::instance()->post('password');
    $user = UserModel::get($mail); 
    $password = $user['password'];
    if($password == $newPassword) {
      return json_encode(
        array(
          'status' => 0,
          'message' => 1
        )
      );
    }
    $user->password = $newPassword;
    if($user->save()){
      return json_encode(
        array(
            'status' => 1,
            'message' => 1
        )
      );
    }else{
      return json_encode(
        array(
            'status' => 0,
            'message' => 0
        )
      );
    }
  }
  public function getSingleUser()
  {
    # code...
  }
  public function getUsers()
  { 
    $mail = Request::instance()->get('mail');
    $userName = Request::instance()->get('userName');
    if ($mail) {
      $user = UserModel::where('mail', 'like','%'.$mail.'%')->select();
    } else if ($userName) {
      $user = UserModel::where('userName', 'like','%'.$userName.'%')->select();
    } else {
      $user = UserModel::all();
    }
    return json_encode(
      array(
        'status' => 1,
        'data' =>  array(
            'users' => $user,
            'count' => count($user)
          )
      )
    ); 
  }
  public function del()
  {
    $mail = Request::instance()->delete('mail');
    $user = UserModel::get($mail);
    if ($user) {
      $user->delete();
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
  public function upData()
  {
    
    $mail = Request::instance()->post('mail');
    if(!$mail){
      return 'options';
    }
    $userName = Request::instance()->post('userName');
    $password = Request::instance()->post('password');
    $user = UserModel::get($mail); 
    $user->userName = $userName;
    $user->password = $password;
    if($user->save()){
      return json_encode(
        array(
            'status' => 1
        )
      );
    }else{
      return json_encode(
        array(
            'status' => 0
        )
      );
    }
  }
}

?>