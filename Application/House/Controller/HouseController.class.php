<?php
namespace House\Controller;
use Think\Controller;

class HouseController extends Controller
{
  public function CreateHouse()
  {
    //  获取post请求中的json数据并解码
    $post_str = file_get_contents('php://input');
    $post = json_decode($post_str,true);
    $ctime = date("Y-m-d H:i",time());
    $memberId = $post['memberId'];
    //  注，大多数操作都需要用户登陆后的token
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

    if(!$t1)
    {
      $arr = array(
        "status" => 20000,
        "message" => "用户未登录！",
        "timestamp" => $ctime,
        "detail" =>array(),
      );
       exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
    // 如果用户的token与session中的token不匹配，则登录信息无效，返回错误
    else if($t1 != $token)
    {
      $arr = array(
        "status" => 10000,
        "message" => "登录信息无效！",
        "timestamp" => $ctime,
        "detail" =>array(),
      );
       exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
    //  如果token匹配，则用户身份确认，继续操作
    else
    {
      // 创建房源
      M("house") -> add($post['detail']);
      // 封装返回的数据格式，注意以这个格式为统一格式，若有返回的数据全部封装在detail中
      $arr = array(
        "status" => 0,
        "message" => "创建成功",
        "timestamp" => $ctime,
        "detail" => array()
      );
      exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
  }
  public function DeleteHouse()
  {
    //  获取post请求中的json数据并解码
    $post_str = file_get_contents('php://input');
    $post = json_decode($post_str,true);
    $ctime = date("Y-m-d H:i",time());
    $memberId = $post['memberId'];
    //  注，大多数操作都需要用户登陆后的token
    $token = $post['token'];
    //  根据用户的memberId找到session中的token，并与用户的token进行比对
    //  如果token相同则代表登录状态正常
    $t1 = session("token".$memberId);
    //  如果token不存在，未登陆，返回错误

    if($memberId != "0001")
    {
      $arr = array(
        "status" => 30000,
        "message" => "用户权限不匹配！",
        "timestamp" => $ctime,
        "detail" => array(),
      );
       exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }

    if(!$t1)
    {
      $arr = array(
        "status" => 20000,
        "message" => "用户未登录！",
        "timestamp" => $ctime,
        "detail" =>array(),
      );
       exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
    // 如果用户的token与session中的token不匹配，则登录信息无效，返回错误
    else if($t1 != $token)
    {
      $arr = array(
        "status" => 10000,
        "message" => "登录信息无效！",
        "timestamp" => $ctime,
        "detail" =>array(),
      );
       exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
    //  如果token匹配，则用户身份确认，继续操作
    else
    {
      // 删除房源
      M("house") -> where("id = '%s'",$post['id']) -> delete();
      // 封装返回的数据格式，注意以这个格式为统一格式，若有返回的数据全部封装在detail中
      $arr = array(
        "status" => 0,
        "message" => "删除成功",
        "timestamp" => $ctime,
        "detail" => array()
      );
      exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
  }
  public function UpdateHouse()
  {
    //  获取post请求中的json数据并解码
    $post_str = file_get_contents('php://input');
    $post = json_decode($post_str,true);
    $ctime = date("Y-m-d H:i",time());
    $memberId = $post['memberId'];
    //  注，大多数操作都需要用户登陆后的token
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

    if(!$t1)
    {
      $arr = array(
        "status" => 20000,
        "message" => "用户未登录！",
        "timestamp" => $ctime,
        "detail" =>array(),
      );
       exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
    // 如果用户的token与session中的token不匹配，则登录信息无效，返回错误
    else if($t1 != $token)
    {
      $arr = array(
        "status" => 10000,
        "message" => "登录信息无效！",
        "timestamp" => $ctime,
        "detail" =>array(),
      );
       exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
    //  如果token匹配，则用户身份确认，继续操作
    else
    {
      // 用id更新房源
      M("house") -> where("id = '%s'",$post['id']) -> save($post['detail']);
      // 封装返回的数据格式，注意以这个格式为统一格式，若有返回的数据全部封装在detail中
      $arr = array(
        "status" => 0,
        "message" => "更新成功",
        "timestamp" => $ctime,
        "detail" => array()
      );
      exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
  }
  public function WatchHouse()
  {
    $ctime = date("Y-m-d H:i",time());
    // 查看房源
    // M("house") -> select();
    // 封装返回的数据格式，注意以这个格式为统一格式，若有返回的数据全部封装在detail中
    $arr = array(
      "status" => 0,
      "message" => "房源信息",
      "timestamp" => $ctime,
      "detail" => array(M("house") -> select())
    );
    exit(json_encode($arr,JSON_UNESCAPED_UNICODE));

  }
}
?>