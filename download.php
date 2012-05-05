<?php
session_start();

Header("Content-type: application/octet-stream");
Header("Accept-Ranges: bytes");
if($_REQUEST['download'] == 'comments_by_me'){
	Header("Content-Disposition: attachment; filename=我发的评论.csv");
}elseif($_REQUEST['download'] == 'comments'){
	Header("Content-Disposition: attachment; filename=所有的评论.csv");
}elseif($_REQUEST['download'] == 'mention'){
	Header("Content-Disposition: attachment; filename=提及我的微博.csv");
}elseif($_REQUEST['download'] == 'weibo'){
	Header("Content-Disposition: attachment; filename=我发的微博.csv");
}elseif($_REQUEST['download'] == 'followers'){
	Header("Content-Disposition: attachment; filename=我的粉丝.csv");
}elseif($_REQUEST['download'] == 'following'){
	Header("Content-Disposition: attachment; filename=我关注的人.csv");
}


echo "\xEF\xBB\xBF".$_SESSION[$_REQUEST['download']];
exit;
?>
