<?php
namespace Admin\Controller;
use Think\Controller;

class AdminController extends Controller{
//用户不存在，密码错误，登陆异常（id和pwd为空）
    public function Login(){                             //定义login方法
    $post_str = file_get_contents('php://input');       //从前端获得小红发来的Json数据
    $post = json_decode($post_str,true);                //解码Json（获得小红发来的id和pwd）
    $adminId = $post['adminId'];                      //传入id和pwd
    $adminPwd = $post['adminPasswd'];

    $find_password = M('Admin') ->where("id='$adminId'")->find();  //按照id在数据库中查到密码并按照下面逻辑做出判断和返回数据
    $password = $find_password['passwd'];
    $ctime = date("Y-m-d H:i",time());
    if($find_password){
      if($adminPwd == $password){
        $str = $adminId.$adminPwd;
        $len = strlen($str)-1;
        for($i=0;$i<20;$i++){
          $num = mt_rand(0,$len);
          $Token .=$str[$num];
        }


        $setSession = array("session_id"=>"1","expire"=>"6400");
        session($setSession);
        session($adminId,$Token);
        $test = I('session.'.$adminId);
        $t1 = session();
        $arr1 = array(               //返回给小红的数据格式
          "status" =>0,
          "message" => "登陆成功！",
          "timestamp" => $ctime,
          "detail" =>array(
            "token"=>$Token,
            "test"=>$test,
            "t1"=>$t1
            )
        );

    exit($json=json_encode($arr1,JSON_UNESCAPED_UNICODE));    //对返回数据进行Json封装并返回给小红

      }
      else{
        $arr = array(
          "status" =>111,
          "message" => "密码错误！",
          "timestamp" => $ctime,
          "detail" =>array()
        );
        exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
      }
    }
    else{
      $arr = array(
        "status" => 20000,
        "message" => "用户不存在！",
        "timestamp" => $ctime,
        "detail" =>array(),
      );

      exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
 }

//获取个人信息CustomerDetail
    public function Detail(){
      $post_str = file_get_contents('php://input');
      $post = json_decode($post_str,true);
      $ctime = date("Y-m-d H:i",time());
      $adminId = $post['adminId'];


      $t1 = session();

      exit(json_encode($t1,JSON_UNESCAPED_UNICODE));
    }
}

 ?>
