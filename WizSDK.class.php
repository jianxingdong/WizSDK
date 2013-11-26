<?php

/**
 *  WizSDK.class.php  为知笔记api操作类
 *
 * @author              LuJunjian <CmsSuper@163.com>
 * @license				http://www.php0.net/
 * @version             0.1
 * @lastmodify			2013-10-25
 */

 
class WizSDK{
	  
	  private $apiurl   = 'http://beta.note.wiz.cn';
	  private $username = '';
	  private $passwd   = '';
	  public  $debug     = true;    //开启debug则每次都执行登录 
 	  
      function __construct($username,$passwd){
	
	      $this->username = $username;
	      $this->passwd = $passwd;
	  }
	  
	  
	  /**
	   *   登录验证 
	   *  该方法执行一次即可,因为wiz笔记内部不需要验证登录,此方法的作用是获取token&kb_guid备其他方法使用
	   */
      public function login(){
            

			if(!file_exists('./user.ini') || $this->debug == false){
	
				ob_start();//开启缓存
				
				//登陆认证
				$url = "http://note.wiz.cn/api/login";

				$post_data = array( "user_id" =>$this->username,"password" =>$this->passwd,"isKeep_password"=>"off","debug"=>"");

				$cookie_jar = tempnam('./temp','cookie');//存放COOKIE的文件

				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, $url);

				curl_setopt($ch, CURLOPT_POST, 1);

				curl_setopt($ch, CURLOPT_HEADER, 0);

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);

				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);  

				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);  //保存cookie信息

				curl_exec($ch);

				curl_close($ch);
				
				$json = ob_get_contents();
				
				//写进配置文件
				@file_put_contents('./user.ini',$json);

				ob_clean();
				
			}else{
			    $json = file_get_contents('./user.ini');
			}
			
			return json_decode($json,true);
	   }
	   
	   
	    //获取用户信息
	   public function getUserInfo($token){
	   
			$token = isset($_GET['token'])?$_GET['token']:$token;
			$url   = $this->apiurl."/api/user/info?client_type=web2.0&api_version=3&token={$token}&_=1385364125279";
			$info  = @file_get_contents($url);
			
			return json_decode($info,true);
	   }

	   
	    //获取目录列表
		public function getDirList($token, $kb_guid){

			$token = isset($_GET['token'])?$_GET['token']:$token;
			$kb_guid = isset($_GET['kb_guid'])?$_GET['kb_guid']:$kb_guid;
			$url   = $this->apiurl."/api/category/all?client_type=web2.0&api_version=3&token={$token}&kb_guid={$kb_guid}&_=1385364126264";
			$info  = @file_get_contents($url);
			
			return json_decode($info,true);
		}

		
		//获取目录下文章列表
		public function getDirDocList($token, $kb_guid, $dir){

			$token   = isset($_GET['token'])?$_GET['token']:$token;
			$kb_guid = isset($_GET['kb_guid'])?$_GET['kb_guid']:$kb_guid;
			$dir     = isset($_GET['dir'])?urlencode($_GET['dir']):$dir;
			$url     = $this->apiurl."/api/document/list?client_type=web2.0&api_version=3&token={$token}&action_cmd=category&action_value={$dir}&kb_guid={$kb_guid}&_=1385366664005";
			$info    = @file_get_contents($url);
			  
			return json_decode($info,true);
		}


		//获取目录下文章详情
		public function getDirDocShow($token, $kb_guid, $document_guid){

	        $token   = isset($_GET['token'])?$_GET['token']:$token;
			$kb_guid = isset($_GET['kb_guid'])?$_GET['kb_guid']:$kb_guid;
			$document_guid = isset($_GET['document_guid'])?$_GET['document_guid']:$document_guid;
			$url     = $this->apiurl."/api/document/info?client_type=web2.0&api_version=3&token={$token}&kb_guid={$kb_guid}&document_guid={$document_guid}&_=1385370541346";
			$info    = @file_get_contents($url);
			
			return json_decode($info,true);
		}
	 
}


