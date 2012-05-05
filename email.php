<?php
session_start();
include_once( 'config.php' );
include_once( 'saet.ex.class.php' );

$email = $_SESSION['email'];
$type = $_REQUEST['email'];
echo $email."<br />";
$email_body = $_SESSION[$type];
$email_body = str_replace("\n","<br />",$email_body);
$email_title = date("Y年n月j日的新浪微博备份");
if($type == "weibo"){
	$email_title .= '(我发表的微博)';
}elseif($type == "comments_by_me"){
	$email_title .= '(我发表的评论)';
}elseif($type == "comments"){
	$email_title .= '(所有跟我有关的评论)';
}elseif($type == "mention"){
	$email_title .= '(所有提及我的微博)';
}elseif($type == "followers"){
	$email_title .= '(我的粉丝)';
}elseif($type == "following"){
	$email_title .= '(我关注的人)';
}



$mail = new SaeMail();
$ret = $mail->quickSend( 
		$email,
		$email_title,
		$email_body,
		"", //email account
		"", // email password
		"", // smtp server
		25 // port
	);
$mail->clean();
if($ret == false){
	var_dump($mail->errno(), $mail->errmsg());
}

echo "\xEF\xBB\xBF发送完毕，请查收邮件。";
echo '<script language="javascript">
		setTimeout("window.close()", 2000);
	</script>';

?>
