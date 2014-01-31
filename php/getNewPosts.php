<?php 
require "../../conf.php";
require "humanTiming.php";
if(isset($_GET['thread_id'])&&is_int((int)$_GET['thread_id'])){
	$thread_id = (int)$_GET['thread_id'];
	
}else{
	return;
}
if (isset($_GET['last_post_id'])&&is_int((int)$_GET['last_post_id'])){
	$last_post_id = (int)$_GET['last_post_id'];
	$mysql_command="SELECT `post_id`,`post_text`,`attachment_type`,`timestamp` FROM `post` WHERE `thread_id`=? AND `post_id`>? ORDER BY `post_id` ASC;";
	$stmt=$pdo->prepare($mysql_command);
	$stmt->bindParam(1, $thread_id);
	$stmt->bindParam(2, $last_post_id);
}else{
	$mysql_command="SELECT `post_id`,`post_text`,`attachment_type`,`timestamp` FROM `post` WHERE `thread_id`=? ORDER BY `post_id` ASC;";
	$stmt=$pdo->prepare($mysql_command);
	$stmt->bindParam(1, $thread_id);
}
$stmt->execute();

echo    "["; 
$response = "";
while ($row = $stmt->fetch()){
$text = str_replace('\\','\\\\',stripcslashes($row['post_text'])); 
$text = str_replace("\"", "\\\"",$text); 
$text = str_replace("\r\n", "\n",$text); 
$text = str_replace("\r", "\n",$text); 
$text = str_replace("\n", "\\n",$text);
$response.="{\"id\":".$row['post_id'].",".
	"\"timestamp\":\"".humanTiming(strtotime($row['timestamp']))."\",".
	"\"attachmentType\":".$row["attachment_type"].",".
	"\"text\":\"".$text."\"".
"},";
} 
$response= rtrim($response, ",");
echo	$response;
echo ']';
$stmt=null;
$pdo=null;
?>