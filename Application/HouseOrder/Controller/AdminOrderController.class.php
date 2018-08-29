<?php
namespace HouseOrder\Controller;
use Think\Controller;

class AdminOrderController extends Controller{

public function DeleteOrder(){}
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
public function UpdateOrder(){}
public function EvaOrder(){}


}




 ?>
