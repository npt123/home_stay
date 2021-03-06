<?php
namespace Customer\Controller;
use Think\Controller;
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:http://localhost:8080');
// 响应类型
header('Access-Control-Allow-Methods:PUT,POST,GET,DELETE');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');

header('Access-Control-Allow-Credentials: true');

class CustomerController extends Controller{

//  Customer模块的Login()函数
//  输入:{
//    memberId:"",
//    memberPasswd:""

//  }
//用户登陆

public function Login(){
        // 从输入流获取原始post数据，原始数据是一串json字符串
        $post_str = file_get_contents('php://input');
        //  通过json_decode方法将json字符串转化为PHP的array对象
        $post = json_decode($post_str,true);
        $memberPwd = $post['memberPasswd'];
        //  从post数中取出memberId和memberPasswd
        $memberEmail = $post['email'];

        if($memberEmail){
          $memberResult= M('Customer')->where("email='%s'",$memberEmail)->find();
              if($memberResult){
                $memberId = $memberResult['id'];
              }
              else{
                $arr = array(
                  "status" => 50001,
                  "message" => "该用户不存在！",
                  "timestamp" => $ctime,
                  "detail" =>array(),
                );
                exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
              }
        }
        else{
          $memberId = $post['memberId'];
        }

        //key用于判断是否在session中已经存在用户信息
        $key = session("token".$memberId);
        //  用M方法从customer中以memberId为限定进行检索
        $find_password = M('Customer') ->where("id='$memberId'")->find();
        //  从数据库的查询结果中急password字段进行比对
        $password = $find_password['passwd'];
        //  获取时间戳，用来封装到返回的信息中
        $ctime = date("Y-m-d H:i",time());
        //先判断session里是否有用户token，以确定是否已经登陆
        if($key){
          $arr = array(
            "status" => 50000,
            "message" => "你已经登陆！",
            "timestamp" => $ctime,
            "detail" =>array(),
          );

          exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
        }
          //  第一层逻辑判定，如果有密码则代表该用户不存在
        else if($find_password){
          //  第二层判定逻辑
          //  将从post中取得的memberPwd和数据库的查询结果进行比对
          if($memberPwd == $password){
            //  这是一段加密算法，用来加密生成token
            $str = $memberId.$memberPwd;
            $len = strlen($str)-1;
            for($i=0;$i<20;$i++){
              $num = mt_rand(0,$len);
              $Token .=$str[$num];
            }

            // 将token存入session中，用以判断登陆状态
            // 注意 session的key不能为纯数字，所以将key设为"token".$memberId,取session时
            //  需要注意
             session("token".$memberId,$Token);

             // 封装返回的数据格式，注意以这个格式为统一格式，若有返回的数据全部封装在detail中
              $arr1 = array(
                "status" =>0,
                "message" => "登陆成功！",
                "timestamp" => $ctime,
                "detail" =>array(
                  "token"=>$Token
                  )
              );
              //通过exit方法返回json数据，注意用json_encode把PHP的array转化成json字符串
              exit($json=json_encode($arr1,JSON_UNESCAPED_UNICODE));

          }
          // 如果密码比对结果不正确，则为密码错误，返回密码错误信息
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
        //  如果find_password为空则代表数据库中没有
        // 该条数据，代表没有这个用户，返回没有用户错误
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
      // 同login，获取post请求中的json数据并解码
      $post_str = file_get_contents('php://input');
      $post = json_decode($post_str,true);
      $ctime = date("Y-m-d H:i",time());
      $memberId = $post['memberId'];
      // 注，大多数操作都需要用户登陆后的token
      $token = $post['token'];
      //  根据用户的memberId找到session中的token，并与用户的token进行比对
      //  如果token相同则代表登录状态正常
      $t1 = session("token".$memberId);
      if(!(M('customer')->where("id = '%s'",$memberId)->find()))
      {
        $arr = array(
          "status" => 30000,
          "message" => "用户权限不匹配！",
          "timestamp" => $ctime,
          "detail" => array(),
        );
         exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
      }
      //  如果token不存在，未登陆，返回错误
      if(!$t1){
        $arr = array(
          "status" => 20000,
          "message" => "用户未登录或用户名错误！",
          "timestamp" => $ctime,
          "detail" =>array(),
        );
         exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
      }
      // 如果用户的token与session中的token不匹配，则登录信息无效，返回错误
      else if($t1 != $token){
          $arr = array(
            "status" => 10000,
            "message" => "登录信息无效！",
            "timestamp" => $ctime,
            "detail" =>array(),
          );
           exit(json_encode($arr,JSON_UNESCAPED_UNICODE));

      }
      //如果token匹配，则用户身份确认，继续操作
      else{
        //从customer表中取出用户的基本信息进行封装，并且返回
        $find_detail = M('Customer') ->where("id='$memberId'")->find();
        $arr = array(
            "status" => 0,
            "message" => "个人数据！",
            "timestamp" => $ctime,
            "detail"=>array($find_detail)
           );
        exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
      }
    }

//修改个人信息
public function Update(){
  $post_str = file_get_contents('php://input');
  $post = json_decode($post_str,true);
  $ctime = date("Y-m-d H:i",time());
  $memberId = $post['memberId'];
  // 注，大多数操作都需要用户登陆后的token
  $token = $post['token'];
  $t1 = session("token".$memberId);
  //  如果token不存在，未登陆，返回错误
  //exit(json_encode($t1,JSON_UNESCAPED_UNICODE));
  if(!(M('customer')->where("id = '%s'",$memberId)->find()))
  {
    $arr = array(
      "status" => 30000,
      "message" => "用户权限不匹配！",
      "timestamp" => $ctime,
      "detail" => array(),
    );
     exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
  }
  if(!$t1){
    $arr = array(
                  "status" => 20000,
                  "message" => "用户未登录或用户名错误！",
                  "timestamp" => $ctime,
                  "detail" =>array(),
                );
     exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
        }
  // 如果用户的token与session中的token不匹配，则登录信息无效，返回错误
 if($t1 != $token){
      $arr = array(
                   "status" => 10000,
                   "message" => "登录信息不匹配，无权修改！",
                   "timestamp" => $ctime,
                   "detail" =>array(),
                 );
       exit(json_encode($arr,JSON_UNESCAPED_UNICODE));

                }
  //如果token匹配，则用户身份确认，继续操作
  else{
    M("Customer") ->where("id='$memberId'")->save($post['detail']);
    $arr = array(
                  "status" => 0,
                  "message" => "修改成功！",
                  "timestamp" => $ctime,
                  "detail" =>array(),
                );
     exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
}

//用户注册
public function Register(){
  $post_str = file_get_contents('php://input');
  $post = json_decode($post_str,true);
  $ctime = date("Y-m-d H:i",time());
  $email = $post['detail']['email'];
  $find_email = M('Customer') -> where("email='$email'")->find();
  //exit(json_encode( $find_email,JSON_UNESCAPED_UNICODE));
  if($find_email){
    $arr = array(
                  "status" => 1000,
                  "message" => "用户已存在！",
                  "timestamp" => $ctime,
                  "detail" =>array(),
                );
     exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
                }
  else{
    M('Customer') -> add($post['detail']);

    $CustomerResult= M('Customer')->where("email='%s'",$post['detail']['email'])->find();

    $MemberId = $CustomerResult['id'];

    $arr = array(
                  "status" => 0,
                  "message" => "注册成功！",
                  "timestamp" => $ctime,
                  "detail" =>array(
                    "memberId" => $MemberId
                  )
                );
     exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
      }
}
//用户登出
public function Logout(){
      $post_str = file_get_contents('php://input');
      $post = json_decode($post_str,true);
      $ctime = date("Y-m-d H:i",time());
      $memberId = $post['memberId'];
      // 注，大多数操作都需要用户登陆后的token
      $token = $post['token'];
      $t1 = session("token".$memberId);
      //  如果token不存在，未登陆，返回错误
      //exit(json_encode($t1,JSON_UNESCAPED_UNICODE));
      if(!(M('customer')->where("id = '%s'",$memberId)->find()))
      {
        $arr = array(
          "status" => 30000,
          "message" => "用户权限不匹配！",
          "timestamp" => $ctime,
          "detail" => array(),
        );
         exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
      }
      else if(!$t1){
        $arr = array(
          "status" => 20000,
          "message" => "用户未登录或用户名错误！",
          "timestamp" => $ctime,
          "detail" =>array(),
        );
         exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
      }
      // 如果用户的token与session中的token不匹配，则登录信息无效，返回错误
     if($t1 != $token){
          $arr = array(
            "status" => 10000,
            "message" => "登录信息不匹配！",
            "timestamp" => $ctime,
            "detail" =>array(),
          );
           exit(json_encode($arr,JSON_UNESCAPED_UNICODE));

      }
      //如果token匹配，则用户身份确认，继续操作
      else{
        session("token".$memberId,null);
        $arr = array(
            "status" => 0,
            "message" => "退出登录！",
            "timestamp" => $ctime,
            "detail"=>array()
        );
        exit(json_encode($arr,JSON_UNESCAPED_UNICODE));

      }


    }


}



 ?>
