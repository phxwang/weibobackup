<?php

session_start();

include_once( 'config.php' );
include_once( 'saet.ex.class.php' );
/*
if( isset($_SESSION['last_key']) ){
	$o = new SaeTOAuth( WB_AKEY , WB_SKEY , $_SESSION['keys']['oauth_token'] , $_SESSION['keys']['oauth_token_secret']  );
	$last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;
	$_SESSION['last_key'] = $last_key;

	echo '<script language="javascript">
		location.href=" http://weibobackup.sinaapp.com/weibolist.php"
		</script>';

}else{
 */

include_once('template.class.php');

$template = new Template("template.tpl");
$content = "";

$o = new SaeTOAuth( WB_AKEY , WB_SKEY  );

$keys = $o->getRequestToken();
$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , 'http://' . $_SERVER['HTTP_APPNAME'] . '.sinaapp.com/callback.php');

$_SESSION['keys'] = $keys;

$content .= '<p>点击下面的图标，用您的新浪微博账号登陆后可以选择要备份的内容并指定备份的邮箱。</p>';
$content .= '<a href="'.$aurl.'"><img src="img/sinaweibo.png"></a>';
$content .= '<p>有任何问题请联系<a href="http://t.sina.com.cn/gossipcoder">陈钢</a></p>';
$content .= '<p>近期要实现的功能：图片备份；由于CSV文件容易出现乱码，且不易排版，我会尽快提供PDF备份功能。</p>';

$template->set('content', $content);
echo $template->output();

//}
?>

