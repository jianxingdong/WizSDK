<?php
include "WizSDK.class.php";

$username = "example@example.com";  //wiz账号
$password = "";       //wiz密码

$wiz  = new WizSDK($username,$password);

$wiz->debug = true;

//登录
$info    = $wiz->login();
$token   = $info['token'];
$kb_guid = $info['kb_guid'];


//获取用户信息
$userinfo = $wiz->getUserInfo($token);

//获取目录列表
$dirinfo = $wiz->getDirList($token, $kb_guid);


if(!isset($_GET['dir']) && !isset($_GET['id'])){
	echo "<ul>";
	foreach($dirinfo['list'] as $dirs){

		 $dir =  urlencode($dirs['location']);
		  
		 echo "<li><a href='?dir=".$dir."'>".$dirs['category_name']."</a></li>";
	}
	echo "</ul>";
}


if(isset($_GET['dir'])){

	 //获取每个目录下笔记列表
	$doclist[] = $wiz->getDirDocList($token, $kb_guid, $_GET['dir']);

   echo "<ul>";
	foreach($doclist as $doc){
   
		 foreach($doc['list'] as $note){

			   
		     echo "<li><a href='?id=".$note['document_guid']."'>".$note['document_title']."</a></li>";
		 }
	}
	
    echo "</ul>";
}



if(isset($_GET['id'])){
     $document_guid = $_GET['id'];
     $info = $wiz->getDirDocShow($token, $kb_guid, $document_guid);
     
     //针对wiz笔记图片相对路径进行补全 
     echo preg_replace ( "/src\='\/unzip\//", "src='http://beta.note.wiz.cn/unzip/", $info['document_info']['document_body'] );
	 
}



