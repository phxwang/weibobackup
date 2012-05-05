<?php
session_start();

include_once( 'config.php' );
include_once( 'saet.ex.class.php' );

include_once('template.class.php');
$template = new Template("template.tpl");

$content = "";
$c = new SaeTClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );

$user = $c->verify_credentials();
#foreach($user as $key => $value){
	#$content .= $key.":".$value."<br />";
	#$s->write('weibobackup','userinfo.txt',$key.":".$value."\n");
#}
#
#
$email = $_REQUEST['email'];
$_SESSION['email'] = $email;

if($_REQUEST['backup_weibo'] != 1
	and $_REQUEST['backup_comments_by_me'] != 1
	and $_REQUEST['backup_comments'] != 1
	and $_REQUEST['backup_mention'] != 1
	and $_REQUEST['backup_fans'] != 1
	and $_REQUEST['backup_following'] != 1){
	$content .=  "你啥都不备份，跑这来干啥？<br />";
}else{
	$content .= "请选择要备份的内容下载，下载后的文件可用微软Excel打开。<br />";
}

if($_REQUEST['backup_weibo'] == 1){
	$msg  = $c->user_timeline(1, 200, null);
	$weibo_text = "";
	$content .= '<a href="download.php?download=weibo">下载我发表的微博</a><br />';
	if($email){
		$content .= '<a href="email.php?email=weibo" target="_blank">把我发表的微博发送到我的邮箱</a><br /><br />';
	}
	foreach ($msg as $data){
		$weibo_text .= $data['created_at'].",".$data['source'].",".$data['text'];
		if($data['retweeted_status']){
			$weibo_text .= ",".$data['retweeted_status']['text']."\n";
		}
	}
	$_SESSION['weibo'] = $weibo_text;
}


if($_REQUEST['backup_comments_by_me'] == 1){
	$comments_by_me = $c->comments_by_me(1, 200);
	$comments_by_me_text = '';	
	$content .= '<a href="download.php?download=comments_by_me">下载我发表的评论</a><br />';
	if($email){
		$content .= '<a href="email.php?email=comments_by_me" target="_blank">把我发表的评论发送到我的邮箱</a><br /><br />';
	}
	foreach( $comments_by_me as $item ){
		$comments_by_me_text .= $item['created_at'].",".$item['text'].",".$item['status']['text']."\n";
	}
	$_SESSION['comments_by_me']=$comments_by_me_text;
}


if($_REQUEST['backup_comments'] == 1){
	$comments = $c->comments_timeline(1, 200);
	$comments_text = "";
	$content .= '<a href="download.php?download=comments">下载所有评论</a><br />';
	if($email){
		$content .= '<a href="email.php?email=comments" target="_blank">把所有评论发送到我的邮箱</a><br /><br />';
	}
	foreach( $comments as $data ){
		$comments_text .= $data['created_at'].",".$data['text'].",".$data['status']['text']."\n";
	}
	$_SESSION['comments']=$comments_text;
}

if($_REQUEST['backup_mention'] == 1){
	$comments = $c->mentions(1, 200);
	$mention_text = "";
	$content .= '<a href="download.php?download=mention">下载所有提及我的微博</a><br />';
	if($email){
		$content .= '<a href="email.php?email=mention" target="_blank">把所有提及我的微博发送到我的邮箱</a><br /><br />';
	}
	foreach( $comments as $item ){
		$mention_text .= $item['created_at'].",".$item['text'].",".$item['status']['text']."\n";
	}
	$_SESSION['mention']=$mention_text;
}

if($_REQUEST['backup_fans'] == 1){
	$content .= '<a href="download.php?download=followers">下载我的粉丝</a><br />';
	if($email){
		$content .= '<a href="email.php?email=followers" target="_blank">把我的粉丝发送到我的邮箱</a><br /><br />';
	}
	
	$follower_list = "";
	$cursor = -1;
	while($cursor != 0){
		$followers = $c->followers($cursor, 200);
		
		foreach($followers['users'] as $follower){
			$follower_list .= $follower['id'] . "," . $follower['screen_name'] . ",". $follower["location"] . "\n";
		}
		$cursor = $followers['next_cursor'];
	}
	$_SESSION['followers'] = $follower_list;
}

if($_REQUEST['backup_following'] == 1){
	$content .= '<a href="download.php?download=following">下载我关注的人</a><br />';
	if($email){
		$content .= '<a href="email.php?email=following" target="_blank">把我关注的人发送到我的邮箱</a><br /><br />';
	}

	$follower_list = "";
	$cursor = -1;
	while($cursor != 0){
		$followers = $c->friends($cursor, 200);
		
		foreach($followers['users'] as $follower){
			$follower_list .= $follower['id'] . "," . $follower['screen_name'] . ",". $follower["location"] . "\n";
		}
		$cursor = $followers['next_cursor'];
	}
	$_SESSION['following'] = $follower_list;
}

if($_REQUEST['send_weibo'] == 1){
	$msg = $c->update("能备份粉丝和好友！可以分类备份微博、评论和提及我微博！可以下载，可以发送到邮箱！我正在测试@chengangcs 开发的多功能新浪微博备份工具：http://weibobackup.sinaapp.com/");
	if ($msg === false || $msg === null){
		$content .= "Error occured";
		return false;
	}
	if (isset($msg['error_code']) && isset($msg['error'])){
		$content .= ('Error_code: '.$msg['error_code'].';  Error: '.$msg['error'] );
		return false;
	} 
}

$template->set('content',$content);
echo $template->output('template.tpl');
?>
