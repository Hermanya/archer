<?php 
require "../../conf.php";
if(isset($_GET['thread_id']) ){
	$thread_id = intval($_GET['thread_id']);
	
}else{
	return;
}
if (isset($_GET['last_post_id'])){
	$last_post_id = intval($_GET['last_post_id']);
	$mysql_command="SELECT `post_id`,`post_text`,`timestamp` FROM `post` WHERE `thread_id`=? AND `post_id`>? ORDER BY `post_id` ASC;";
	$stmt=$pdo->prepare($mysql_command);
	$stmt->bindParam(1, $thread_id);
	$stmt->bindParam(2, $last_post_id);
}else{
	$mysql_command="SELECT `post_id`,`post_text`,`timestamp` FROM `post` WHERE `thread_id`=? ORDER BY `post_id` ASC;";
	$stmt=$pdo->prepare($mysql_command);
	$stmt->bindParam(1, $thread_id);
}
$stmt->execute();

echo    "["; 
$response = "";
while ($row = $stmt->fetch()){
	if (file_exists("../content/".$thread_id."/".$row['post_id'])){
		$attachment_type=1;
	}else{
		$attachment_type=0;
	}
$text = str_replace('\\','\\\\',stripcslashes($row['post_text'])); 
$text = str_replace("\"", "\\\"",$text); 
$text = str_replace("\r\n", "\n",$text); 
$text = str_replace("\r", "\n",$text); 
$text = str_replace("\n", "\\n",$text);
$response.="{\"id\":".$row['post_id'].",".
	"\"timestamp\":\"".strtotime($row['timestamp'])."\",".
	"\"attachmentType\":".$attachment_type.",".
	"\"text\":\"".$text."\"".
"},";
} 
$response= rtrim($response, ",");
echo	$response;
echo ']';
$stmt=null;
$pdo=null;
?>