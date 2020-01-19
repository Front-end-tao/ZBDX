<?php
namespace app\index\controller;
use think\Db;
use think\Controller;

class Index extends Controller
{
    public function index()
    {   
        $phoneNumber = $this->request->post();
        return json($phoneNumber);

        // $params = $this->request->delete('id');
        // $params = $this->required(['id/d']);
        
        // return json($params);

        // return $params;
        // $phoneNumber = request()->get('phoneNumber');
        // // $require = $this->required(['phoneNumber/d','name/d']);
        // // echo $phoneNumber;
        // // echo $require;
        // // $phoneNumber = request()->post('phoneNumber');
        // return $phoneNumber;

        // $request = Request::instance();
        // echo 'demo'.$result->ip();
        // 赋值给模板变量
        // $name = '今天我的滑板鞋';
        // $email = '摩擦摩擦';
        // $this->assign('name',$name);
        // $this->assign('email',$email);
        // // return $this->fetch('test');
        // return '欢迎使用tp5框架';
    }
    public function register()
    {
        $phoneNumber = $this->request->post('phoneNumber');
        return json($phoneNumber);
    }
    // --------------------------------------------------
    // ---                  原生查询                   ---
    // --------------------------------------------------
    // public function create()
    // {
    //     $result = Db::execute('insert into userInfo (phoneNumber,nickName,password,email) values (18406586082,"梁涛",19971102,"lt971102@163.com")');
    //     dump($result);
    // }
    // public function update()
    // {
    //     $result = Db::execute('update userInfo set email = "1550229128@qq.com" where phoneNumber = 18406586080');
    //     dump($result);
    // }
    // public function read()
    // {
    //     $result = Db::query(
    //         'select * from userInfo where password = ?',
    //         [19971102]
    //     );
    //     dump($result);
    // }
    // public function delete()
    // {
    //     $result = Db::execute('delete from userInfo where password = 19971102');
    //     dump($result);
    // }
    // public function show()
    // {
    //     $result = Db::query('show tables from userInfo');
    //     dump($result);
    // }
    // --------------------------------------------------
    // ---                 查询构造器                  ---
    // --------------------------------------------------
    public function create()
    {
        try {
            $result = Db::table('userInfo')
            ->insert(['phoneNumber' => 18406560925, 'nickName' => '查询构造器', 'password' => 19971102, 'email' => '155022@qq.com']);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function update()
    {
        $result = Db::table('userInfo')
                            ->where('password',19971102)
                            ->update(['password' => 20117991]);
    }
    public function read()
    {
        $result = Db::table('userinfo')
                            ->where('password',20117991)
                            ->select();
                            // ->find();
        dump($result);
    }
    public function delete()
    {
        $result = Db::table('userInfo')
                            ->where('phoneNumber',1)
                            ->delete();
        echo $result;
    }
    public function hello()
    {
        echo 'hello';
        # code...
    }
    public function mail(){
 
        $code = rand('100000','999999');        //六位随机数
        $toemail= '1550229128@qq.com';            //收件人的邮箱
        $to_name = '你是猪';                    //设置收件人信息，如邮件格式说明中的收件人
        $title = '这是一份测试邮件！';    
        $content = "哇哈哈哈哈哈哈，鹅~鹅~鹅~鹅~鹅~鹅~鹅~鹅~鹅~鹅~鹅~鹅~鹅~鹅~鹅~鹅~鹅~鹅~鹅~鹅~鹅~鹅~笑出鹅叫，您的验证码为：【{$code}】";
 
 
        $sendmail = 'lt971102@163.com';     //发件人邮箱
        $sendmailpswd = "liangtao123";              //客户端授权密码,而不是邮箱的登录密码，就是手机发送短信之后弹出来的一长串的密码
        $send_name = '本大爷';                // 设置发件人信息，如邮件格式说明中的发件人，
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
           return json(["code"=>400,"result"=>$mail->ErrorInfo,"data"=>'']);//返回数据格式自己定义的一个函数
 
        }else{
            return json(["code"=>200,"result"=>"发送成功","data"=>'']);
        }
        return 1;
    }
}
