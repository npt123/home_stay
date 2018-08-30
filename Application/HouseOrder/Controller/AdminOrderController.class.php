<?php
namespace HouseOrder\Controller;
use Think\Controller;
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:http://localhost:8080');
// 响应类型
header('Access-Control-Allow-Methods:PUT,POST,GET,DELETE');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');

header('Access-Control-Allow-Credentials: true');
class AdminOrderController extends Controller{

//删除订单
public function DeleteOrder(){
  $post_str = file_get_contents('php://input');
  $post = json_decode($post_str,true);
  $ctime = date("Y-m-d H:i",time());
  $memberId = $post['memberId'];
  // 注，大多数操作都需要用户登陆后的token
  $token = $post['token'];
  $t1 = session("token".$memberId);
  //  如果token不存在，未登陆，返回错误
  //exit(json_encode($t1,JSON_UNESCAPED_UNICODE));
  if(!(M('admin')->where("id = '%s'",$memberId)->find()))
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
          // "token"=>$token,
          // "t1"=>$t1
        ),
      );
       exit(json_encode($arr,JSON_UNESCAPED_UNICODE));

  }
  //如果token匹配，则用户身份确认，继续操作
  else{
    $orderId = $post['orderId'];
    $find_orderId = M('house_order') -> where("id='$orderId'")->find();
    if ($find_orderId){
      M("House_order") ->where("id='$orderId'")->delete();
      $arr = array(
                    "status" => 0,
                    "message" => "删除成功！",
                    "timestamp" => $ctime,
                    "detail" =>array()
                  );
      exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
    else{
      $arr = array(
                    "status" => 1000,
                    "message" => "所要删除订单不存在！",
                    "timestamp" => $ctime,
                    "detail" =>array()
                  );
      exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
  }
}

//查询订单
public function CheckOrder(){
  $post_str = file_get_contents('php://input');
  $post = json_decode($post_str,true);
  $ctime = date("Y-m-d H:i",time());
  $memberId = $post['memberId'];
  // 注，大多数操作都需要用户登陆后的token
  $token = $post['token'];
  //  根据用户的memberId找到session中的token，并与用户的token进行比对
  //  如果token相同则代表登录状态正常
  $t1 = session("token".$memberId);
  //  如果token不存在，未登陆，返回错误
  if(!(M('admin')->where("id = '%s'",$memberId)->find()))
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
      "message" => "商家未登录或商家用户名错误！",
      "timestamp" => $ctime,
      "detail" =>array(),
    );
     exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
  }
  // 如果用户的token与session中的token不匹配，则登录信息无效，返回错误
  else if($t1 != $token){
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

                  $find_order = M("house_order") -> where("memberId='$memberId'") ->select();
                  $ordernum = count($find_order);
                  //六条记录一组存储
                  $j =0;
                  for($i=0;$i<$ordernum;$i++){
                    $order[$j][$i-(6*$j)]=$find_order[$i];
                    if(count($order[$j])>5){$j=$j+1;}
                  }
                  $arr = array(
                  "status" => 0,
                  "message" => "管理员订单信息！",
                  "timestamp" => $ctime,
                  "detail" =>array("orderInfo"=>$order)
                  );
                  exit(json_encode($arr,JSON_UNESCAPED_UNICODE));

            }

}

//接受订单
public function AcceptOrder(){
  $post_str = file_get_contents('php://input');
  $post = json_decode($post_str,true);
  $ctime = date("Y-m-d H:i",time());
  $memberId = $post['memberId'];
  // 注，大多数操作都需要用户登陆后的token
  $token = $post['token'];
  $t1 = session("token".$memberId);
  //  如果token不存在，未登陆，返回错误
  //exit(json_encode($t1,JSON_UNESCAPED_UNICODE));
  if(!(M('admin')->where("id = '%s'",$memberId)->find()))
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
          // "token"=>$token,
          // "t1"=>$t1
        ),
      );
       exit(json_encode($arr,JSON_UNESCAPED_UNICODE));

  }
  //如果token匹配，则用户身份确认，继续操作
  else{
    $orderId = $post['orderId'];
    $find_orderId = M('house_order') -> where("id='$orderId'")->find();
    if ($find_orderId){
      $data['status'] = 'accepted';
      M("House_order") ->where("id='$orderId'")->data($data)->save();
      $arr = array(
                    "status" => 0,
                    "message" => "接受成功！",
                    "timestamp" => $ctime,
                    "detail" =>array()
                  );
      exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
    else{
      $arr = array(
                    "status" => 1000,
                    "message" => "所要接受订单不存在！",
                    "timestamp" => $ctime,
                    "detail" =>array()
                  );
      exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
  }
}

//拒绝订单
public function RefuseOrder(){
  $post_str = file_get_contents('php://input');
  $post = json_decode($post_str,true);
  $ctime = date("Y-m-d H:i",time());
  $memberId = $post['memberId'];
  // 注，大多数操作都需要用户登陆后的token
  $token = $post['token'];
  $t1 = session("token".$memberId);
  //  如果token不存在，未登陆，返回错误
  //exit(json_encode($t1,JSON_UNESCAPED_UNICODE));
  if(!(M('admin')->where("id = '%s'",$memberId)->find()))
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
          // "token"=>$token,
          // "t1"=>$t1
        ),
      );
       exit(json_encode($arr,JSON_UNESCAPED_UNICODE));

  }
  //如果token匹配，则用户身份确认，继续操作
  else{
    $orderId = $post['orderId'];
    $find_orderId = M('house_order') -> where("id='$orderId'")->find();
    if ($find_orderId){
      $data['status'] = 'refused';
      M("House_order") ->where("id='$orderId'")->data($data)->save();
      $arr = array(
                    "status" => 0,
                    "message" => "拒绝成功！",
                    "timestamp" => $ctime,
                    "detail" =>array()
                  );
      exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
    else{
      $arr = array(
                    "status" => 1000,
                    "message" => "所要拒绝订单不存在！",
                    "timestamp" => $ctime,
                    "detail" =>array()
                  );
      exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
  }
}

//确认订单
public function ConfirmOrder(){
  $post_str = file_get_contents('php://input');
  $post = json_decode($post_str,true);
  $ctime = date("Y-m-d H:i",time());
  $memberId = $post['memberId'];
  // 注，大多数操作都需要用户登陆后的token
  $token = $post['token'];
  $t1 = session("token".$memberId);
  //  如果token不存在，未登陆，返回错误
  //exit(json_encode($t1,JSON_UNESCAPED_UNICODE));
  if(!(M('admin')->where("id = '%s'",$memberId)->find()))
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
          // "token"=>$token,
          // "t1"=>$t1
        ),
      );
       exit(json_encode($arr,JSON_UNESCAPED_UNICODE));

  }
  //如果token匹配，则用户身份确认，继续操作
  else{
    $orderId = $post['orderId'];
    $find_orderId = M('house_order') -> where("id='$orderId'")->find();
    if ($find_orderId){
      $data['status'] = 'confirmed';
      M("House_order") ->where("id='$orderId'")->data($data)->save();
      $arr = array(
                    "status" => 0,
                    "message" => "确认成功！",
                    "timestamp" => $ctime,
                    "detail" =>array()
                  );
      exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
    else{
      $arr = array(
                    "status" => 1000,
                    "message" => "所要确认订单不存在！",
                    "timestamp" => $ctime,
                    "detail" =>array()
                  );
      exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
  }
}


}




 ?>
