<?php
error_reporting(0);
session_start();
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__))."/");
define('ROOT_PATH',str_replace('app/','',BASE_PATH));
define('MSGFILE','app/MSG.txt'); 
define('NUMFILE','app/NUM.txt'); 
define('KEYS','bskdl87'); 
define('LSTR','admin');
date_default_timezone_set('PRC');
function nick($user=''){
if(empty($user)){ 
    $name=rand_nick();
}else{
    $name = $user;
} 
    $arr['msg'] = '<span>'.date('').'</span>';
	$arr['type']= 'sys';
	$str = json_encode($arr);
	$arr['msg'] = '<span class="tips-warning">通知：<strong>'.$name.'</strong>进入留言室</span>';
	$arr['type']= 'sys';
	$str = $str."\n".json_encode($arr);
	file_put_contents(ROOT_PATH.MSGFILE, $str."\n" , FILE_APPEND|LOCK_EX);
	$key = uniqid();
    setcookie(KEYS.'_key',$key,time()+3600*24*90,'/');
    setcookie(KEYS.'_name',urlencode($name),time()+3600*24*30,'/');
    return array('name'=>$name,'key'=>$key); //输出生成的昵称
} 

function rand_nick(){
  $name_tou=array('','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');

  $name_wei=array('','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
  $t=rand(0,199);
  $w=rand(0,199);
  $name=$name_tou[$t].$name_wei[$w];
  return $name;
}
function logmsg($b, $msg = '操作成功！')
{
    if ($b > 0) {
        $arr['result'] = 200;
        $arr['message'] = $msg;
    } else {
        $arr['result'] = 500;
        if (empty($msg)) {
            $arr['message'] = '操作失败！';
        } else {
            $arr['message'] = $msg;
        }
    }
    $arr['id'] = $b;
    echo json_encode($arr);
    exit;
}
 

function get_token(){
  if(empty($_SESSION[KEY.'token'])){
      $token = md5(uniqid(rand(), true));
      $_SESSION[KEY.'token'] = $token;
  }else{
     $token = $_SESSION[KEY.'token'];
  }
  setcookie(md5(KEY.'token'),$token,time()+3600*24,'/');
  return $token;
}

function check_post($arr,$config=array()){
	$now = time();
    $token = $_COOKIE[md5(KEY.'token')];	
	if(empty($token) or $token !=  $_SESSION[KEY . 'token']){
	   return false;
	}	
	return true;
}