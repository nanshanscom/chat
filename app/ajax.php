<?php
require_once 'app.php';
$c = isset($_GET['c'])?$_GET['c']:'';
$k = isset($_GET['k'])?intval($_GET['k']):0;
switch ($c) {
	 case "login":
		  //$arr['name'] = empty($_COOKIE[KEYS.'_name'])?nick():urldecode($_COOKIE[KEYS.'_name']);
	      //$arr['key'] = $_COOKIE[KEYS.'_key'];
		  $ref = $_SERVER["HTTP_REFERER"];
		  $url = parse_url($ref);		  
		  if($url['query']==LSTR){
		     $_SESSION[KEY.'login'] = 'admin';			  
		  } 
		  $user = mb_substr(strip_tags($_GET['n']),0,5,'utf-8');
		  if(empty($_COOKIE[KEYS.'_name'])){
		      $arr = nick($user);
		  }else{
			  $arr['name'] =urldecode($_COOKIE[KEYS.'_name']);
		      $arr['key'] = $_COOKIE[KEYS.'_key'];
		  }	 
		  get_token();
		  echo json_encode($arr); 
		  break;
	case 'send';
	$arr['msg'] = strip_tags($_POST['msg']);
	$arr['name'] = strip_tags(urldecode($_COOKIE[KEYS.'_name']));
	$arr['key'] = strip_tags(urldecode($_COOKIE[KEYS.'_key']));
	if(check_post($arr) == false){
	   //logmsg(0);
	   $arr['type']= 'msg';
	   //$arr['msg']= '内容发送失败！';
	   $str = json_encode($arr);
	   exit($str);
	}
	$_SESSION[KEY.'time'] = time();
	unset($_SESSION[KEY . 'token']);
    get_token();
    $arr['msg'] = mb_substr($arr['msg'],0,140,'utf-8');
	if($arr['msg'] =='清理' && $_SESSION[KEY.'login'] == 'admin'){
	   file_put_contents(ROOT_PATH.MSGFILE, '' , LOCK_EX);
       $arr['type'] = 'sys';
	   $arr['msg'] = '清理';
	   $str = json_encode($arr);
	}else{
	  $arr['type']= 'msg';
	  $_SESSION[KEY.'msg'] = $arr['msg'];
	  $str = json_encode($arr);
	  file_put_contents(ROOT_PATH.MSGFILE, $str."\n" , FILE_APPEND|LOCK_EX);
	}		
    //addnum(1);
	echo $str;
	break;
case 'msg':
	//$sk = $_
    $str = file_get_contents(ROOT_PATH.MSGFILE);
    $arr = explode("\n",$str);
	$count = count($arr)-1;
	//echo ($count);exit();
	if($k==$count){
	  $msg['count'] = $count;
	  $msg['list'] = [];
	  echo json_encode($msg);
	  exit();
	}
	if($k>count($arr)-1){
        $msg['type'] = 'sys';
        $msg['msg'] = 'rebot';
		$res['list'][] = json_encode($msg);
        $res['count'] = $count;
        echo json_encode($res); 
		exit();
	}
	//$k = $k==0?$k:$k+1;
	if($k<($count-50)){
	   $k= $count-50;
	}
	$arr = array_slice($arr,$k);
	array_pop($arr);
	$msg['count'] = $count;
	$msg['list'] = $arr;
	echo json_encode($msg);
	break;
}