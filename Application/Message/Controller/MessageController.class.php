<?php
namespace Message\Controller;
use Think\Controller;
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:http://localhost:8080');
// 响应类型
header('Access-Control-Allow-Methods:PUT,POST,GET,DELETE');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');

header('Access-Control-Allow-Credentials: true');

class MessageController extends Controller
{
  public function DeleteMessage()
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
        "detail" => array(),
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
      // 用id删除留言
      M("message") -> where("id = '%s'",$post['detail']['id']) -> delete();
      // 封装返回的数据格式，注意以这个格式为统一格式，若有返回的数据全部封装在detail中
      $arr = array(
        "status" => 0,
        "message" => "删除成功",
        "timestamp" => $ctime,
        "detail"=>array()
      );
      exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
  }

  public function UpdateMessage()
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
      // 用id更新留言
      M("message") -> where("id = '%s'",$post['detail']['id']) -> save($post['detail']);
      // 封装返回的数据格式，注意以这个格式为统一格式，若有返回的数据全部封装在detail中
      $arr = array(
        "status" => 0,
        "message" => "更新成功",
        "timestamp" => $ctime,
        "detail"=>array()
      );
      exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
  }

  public function CusMessage()
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
      // 创建留言
      M("message") -> add($post['detail']);
      // 封装返回的数据格式，注意以这个格式为统一格式，若有返回的数据全部封装在detail中
      $arr = array(
        "status" => 0,
        "message" => "留言成功",
        "timestamp" => $ctime,
        "detail" => array()
      );
      exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
  }

  public function ReadMessage()
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
      // 用id更新留言
      $status['status'] = $post['detail']['status'];
      M("message") -> where("id = '%s'",$post['detail']['id']) -> save($status);
      // 封装返回的数据格式，注意以这个格式为统一格式，若有返回的数据全部封装在detail中
      $arr = array(
        "status" => 0,
        "message" => "更新成功",
        "timestamp" => $ctime,
        "detail"=> array()
      );
      exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
  }

  public function CusSearchMessage()
  {
    //  获取post请求中的json数据并解码
    $post_str = file_get_contents('php://input');
    $post = json_decode($post_str,true);
    $ctime = date("Y-m-d H:i",time());
    $memberId = $post['memberId'];
    $detail = $post['detail'];
    //  注，大多数操作都需要用户登陆后的token
    $token = $post['token'];
    //  根据用户的memberId找到session中的token，并与用户的token进行比对
    //  如果token相同则代表登录状态正常
    $t1 = session("token".$memberId);
    //  如果token不存在，未登陆，返回错误

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
      // 无条件查询
      if(!$detail)
      {
        $find_message = M("message") -> where("cus_id='$memberId'") ->select();
        $messagenum = count($find_message);
        $j =0;
        for($i=0;$i<$messagenum;$i++)
        {
          $message[$j][$i-(6*$j)]=$find_message[$i];
          if(count($message[$j])>5)
          {
            $j=$j+1;
          }
        }
        $arr = array(
          "status" => 0,
          "message" => "无条件查询用户留言信息！",
          "timestamp" => $ctime,
          "detail" =>array($message)
        );
        exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
      }
      //else{exit(json_encode($detail,JSON_UNESCAPED_UNICODE));}
      //有条件查询
      else
      {
        $condition['cus_id']=$post['memberId'];
        if($detail['id'] != null)           {$condition['id']=$detail['id'];}
        if($detail['status'] != null)       {$condition['status']=$detail['status'];}
        if($detail['admin_id'] != null)     {$condition['admin_id']=$detail['admin_id'];}
        if($detail['CustomerDate'] != null) {$condition['CustomerDate']=$detail['CustomerDate'];}
        if($detail['ReplyDate'] != null)    {$condition['ReplyDate']=$detail['ReplyDate'];}
        $find_message = M("message") -> where($condition) ->select();
        $messagenum = count($find_message);
        $j =0;
        for($i=0;$i<$messagenum;$i++)
        {
          $message[$j][$i-(6*$j)]=$find_message[$i];
          if(count($message[$j])>5){$j=$j+1;}
        }
        $arr = array(
          "status" => 0,
          "message" => "用户订单信息！",
          "timestamp" => $ctime,
          "detail" =>array($message)
        );
        exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
      }
    }
  }

  public function AdmSearchMessage()
  {
    //  获取post请求中的json数据并解码
    $post_str = file_get_contents('php://input');
    $post = json_decode($post_str,true);
    $ctime = date("Y-m-d H:i",time());
    $memberId = $post['memberId'];
    $detail = $post['detail'];
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
      // 无条件查询
      if(!$detail)
      {
        $find_message = M("message") -> select();
        $messagenum = count($find_message);
        $j =0;
        for($i=0;$i<$messagenum;$i++)
        {
          $message[$j][$i-(6*$j)]=$find_message[$i];
          if(count($message[$j])>5)
          {
            $j=$j+1;
          }
        }
        $arr = array(
          "status" => 0,
          "message" => "无条件查询用户留言信息！",
          "timestamp" => $ctime,
          "detail" =>array($message)
        );
        exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
      }
           //else{exit(json_encode($detail,JSON_UNESCAPED_UNICODE));}
           //有条件查询
      else
      {
        if($detail['id'] != null)           {$condition['id']=$detail['id'];}
        if($detail['status'] != null)       {$condition['status']=$detail['status'];}
        if($detail['cus_id'] != null)       {$condition['cus_id']=$detail['cus_id'];}
        if($detail['admin_id'] != null)     {$condition['admin_id']=$detail['admin_id'];}
        if($detail['CustomerDate'] != null) {$condition['CustomerDate']=$detail['CustomerDate'];}
        if($detail['ReplyDate'] != null)    {$condition['ReplyDate']=$detail['ReplyDate'];}
        $find_message = M("message") -> where($condition) ->select();
        $messagenum = count($find_message);
        $j =0;
        for($i=0;$i<$messagenum;$i++)
        {
          $message[$j][$i-(6*$j)]=$find_message[$i];
          if(count($message[$j])>5){$j=$j+1;}
        }
        $arr = array(
          "status" => 0,
          "message" => "用户订单信息！",
          "timestamp" => $ctime,
          "detail" =>array($message)
        );
        exit(json_encode($arr,JSON_UNESCAPED_UNICODE));
      }
    }
  }
}
?>
