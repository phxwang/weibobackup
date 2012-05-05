<?php
session_start();
include_once( 'config.php' );
include_once( 'saet.ex.class.php' );
include_once('template.class.php');
$c = new SaeTClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );

$content = "";
if($_FILES["following_list"]["error"] > 0){
	echo "ERROR: " . $_FILES["following_list"]["error"] . "<br />";
}
$list = file($_FILES['following_list']['tmp_name']);

$msg = $c->oauth->get("http://api.t.sina.com.cn/account/rate_limit_status.json");
$limit = $msg['remaining_hits'];
foreach($msg as $key=>$value){
	echo $key.":".$value."<br />";
}

$count = 0;
foreach ($list as $line_num => $line){
	echo trim($line)."<br />";
	$fields = preg_split("/,/",trim($line));
	if($count < $limit){
		$msg = $c->follow($fields[0]);
		if ($msg === false || $msg === null){
			$content .= "Error occured" . "<br />";
			#return false;
		}
		if (isset($msg['error_code']) && isset($msg['error'])){
			$content .= 'Error_code: '.$msg['error_code'].';  Error: '.$msg['error'] . "<br />";
			#return false;
		}
		if (isset($msg['screen_name'])){
			$content .= 'New Friend:'.$msg['screen_name']."<br />";
		}
		if($msg['error_code'] == "403"){
			$content .= "已到本时间段添加关注上限，请明日再来，谢谢<br />";
			break;
		}
		$count++;
	}else{
		$content .= "已到本时间段添加关注上限，请明日再来，谢谢<br />";
	}
}

		
if($_REQUEST['send_weibo'] == 1){
	$msg = $c->update("可以分类备份微博、评论和提及我微博！可以下载，可以发送到邮箱！我正在测试@陈钢长沙 开发的多功能新浪微博备份工具：http://weibobackup.sinaapp.com/");
	if ($msg === false || $msg === null){
		$content .= "Error occured";
		return false;
	}
	if (isset($msg['error_code']) && isset($msg['error'])){
		$content .= ('Error_code: '.$msg['error_code'].';  Error: '.$msg['error'] );
		return false;
	} 
}
echo $content;
?>
