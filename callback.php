<?php
session_start();

include_once( 'config.php' );
include_once( 'saet.ex.class.php' );
include_once('template.class.php');
$template = new Template("template.tpl");

$content = "";

$o = new SaeTOAuth( WB_AKEY , WB_SKEY , $_SESSION['keys']['oauth_token'] , $_SESSION['keys']['oauth_token_secret']  );

$last_key = $o->getAccessToken( $_REQUEST['oauth_verifier'] ) ;

$_SESSION['last_key'] = $last_key;

$content .= '认证通过，请选择您需要的服务：

<form action="weibolist.php" method="post">
<h2>新浪微博好友备份</h2>
<input type="checkbox" name="backup_fans" value="1" checked>备份我的粉丝</input><br />
<input type="checkbox" name="backup_following" value="1" checked>备份我关注的人</input><br />

<h2>新浪微博和评论备份：</h2>
<input type="checkbox" name="backup_weibo" value="1" checked>我发的微博</input><br />
<input type="checkbox" name="backup_comments_by_me" value="1">我发的评论</input><br />
<input type="checkbox" name="backup_comments" value="1">我发的评论和别人对我的评论</input><br />
<input type="checkbox" name="backup_mention" value="1">提到我的微博或评论</input><br />
</p>
<p>如果需要将备份内容发送到邮箱的话，请填写电子邮件地址:<br />
<input type="text" size="40" name="email" /><br />
（推荐使用新浪邮箱）<p>
<input type="submit" value="提交"><br /><br />
<input type="checkbox" name="send_weibo" value="1" checked>发微博把这个好工具告诉朋友们</input><br />
</form>
';
/*
<form action="restore.php" method="post" enctype="multipart/form-data">
<h2>新浪微博关注的人恢复：</h2>
<input type="file" id="following_list" name="following_list"/><br />
<input type="submit" value="提交"><br /><br />
<input type="checkbox" name="send_weibo" value="1" checked>发微博把这个好工具告诉朋友们</input><br />
</form>
';*/

$template->set('content',$content);
echo $template->output();

?>
