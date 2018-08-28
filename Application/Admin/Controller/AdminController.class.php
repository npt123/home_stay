<?php
namespace Admin\Controller;
use Think\Controller;

class AdminController extends Controller{
//管理员登陆
public function Login(){
        // 从输入流获取原始post数据，原始数据是一串json字符串
        $post_str = file_get_contents('php://input');
        //  通过json_decode方法将json字符串转化为PHP的array对象
        $post = json_decode($post_str,true);
        //  从post数中取出memberId和memberPasswd
        $adminId = $post['adminId'];
        $adminPwd = $post['adminPasswd'];
        //key用于判断是否在session中已经存在用户信息
        $key = session("token".$adminId);
        //  用M方法从customer中以memberId为限定进行检索
        $find_password = M('Admin') ->where("id='$adminId'")->find();
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
          if($adminPwd == $password){
            //  这是一段加密算法，用来加密生成token
            $str = $adminId.$adminPwd;
            $len = strlen($str)-1;
            for($i=0;$i<20;$i++){
              $num = mt_rand(0,$len);
              $Token .=$str[$num];
            }

            // 将token存入session中，用以判断登陆状态
            // 注意 session的key不能为纯数字，所以将key设为"token".$memberId,取session时
            //  需要注意
             session("token".$adminId,$Token);

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
              "detail" =>array(
                "adminid"=>$adminId,
                "adminpwd"=>$adminPwd,
                "passwd"=>$password
              )
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

//管理员登出
public function Logout(){
      $post_str = file_get_contents('php://input');
      $post = json_decode($post_str,true);
      $ctime = date("Y-m-d H:i",time());
      $adminId = $post['adminId'];
      // 注，大多数操作都需要用户登陆后的token
      $token = $post['token'];
      $t1 = session("token".$adminId);
      //  如果token不存在，未登陆，返回错误
      //exit(json_encode($t1,JSON_UNESCAPED_UNICODE));
      if(!$t1){
        $arr = array(
          "status" => 20000,
          "message" => "商家未登录或商家用户名错误！",
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
            "detail" =>array(
              "token"=>$token,
              "t1"=>$t1
            ),
          );
           exit(json_encode($arr,JSON_UNESCAPED_UNICODE));

      }
      //如果token匹配，则用户身份确认，继续操作
      else{
        session_unset("token".$adminId);
        $arr = array(
            "status" => 0,
            "message" => "退出登录！",
            "timestamp" => $ctime,
            "detail"=>array()
        );
        exit(json_encode($arr,JSON_UNESCAPED_UNICODE));

      }


    }

//房态管理
    public function UpdateHouse(){
      // 同login，获取post请求中的json数据并解码
      $post_str = file_get_contents('php://input');
      $post = json_decode($post_str,true);
      $ctime = date("Y-m-d H:i",time());
      $adminId = $post['adminId'];
      // 注，大多数操作都需要用户登陆后的token
      $token = $post['token'];
      //  根据用户的memberId找到session中的token，并与用户的token进行比对
      //  如果token相同则代表登录状态正常
      $t1 = session("token".$adminId);

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
            "detail"=>array(
              "memberInfo"=>$find_detail
            )
        );
        exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
      }
    }

}

 ?>
