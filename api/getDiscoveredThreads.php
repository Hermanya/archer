<?php 
switch($_COOKIE["layout"]){
	case 1:
	$maxPostLength = 140;
	break;
	case 0:
	$maxPostLength = 440;
	break;
	default:
	$maxPostLength = 240;
	break;

}
$date = new DateTime();
if ( isset($_GET['max']) ){
	$last_update = intval($_GET['max']);
	if ($last_update<0) {
		$last_update = $date->getTimestamp();
	}
}else{
	$last_update = $date->getTimestamp();
}
if ( isset($_GET['limit']) ){
	$limit = intval($_GET['limit']);
	if ($limit<0) {
		$limit = 1;
	}
}else{
	$limit = 10;
}
	$userTags = explode(' ',$_COOKIE['tag'].$_COOKIE['atag']);
require "../../conf.php";

$mysql_command="SELECT tr.thread_id, tr.last_update
FROM thread tr
WHERE tr.last_update<".$last_update."  
	AND EXISTS (SELECT * FROM thread_tag tt
	WHERE tt.thread_id= tr.thread_id
	AND tt.tag_id = 138)
AND  NOT EXISTS (SELECT * FROM tag ta
	WHERE tag_id IN (";
		$tag_id_values = "";
		foreach ($userTags as $key => $value){
			$tag_id_values.=" ?,";
		}
		$tag_id_values = rtrim($tag_id_values, ",");
		$mysql_command.=$tag_id_values;
		$mysql_command.=") AND EXISTS
(SELECT * FROM thread_tag tt
	WHERE tt.thread_id= tr.thread_id
	AND tt.tag_id = ta.tag_id))
ORDER BY tr.last_update desc
LIMIT 0, ".$limit.";";
$stmt=$pdo->prepare($mysql_command);
$stmt->execute($userTags);
$thread_ids = $stmt->fetchAll();
if (count($thread_ids)!=0) {

	$mysql_command="SELECT `p`.`thread_id`,`p`.`post_id`,`p`.`post_text`, `posts_number`, `ts`
	FROM  (
		SELECT `t1`.`thread_id`, MIN(`t1`.`post_id`) as `op_post_id`, 
		COUNT(`t1`.`post_id`) as `posts_number`, `t2`.`last_update` as `ts`
		FROM `post` as `t1`
		JOIN `thread` AS `t2` ON `t1`.`thread_id` = `t2`.`thread_id`
		WHERE `t1`.`thread_id` IN ( ";
			$thread_id_values = "";
			foreach ($thread_ids as $key => $value){
				$thread_id_values.=$value[0].",";
			}
			$thread_id_values=rtrim($thread_id_values, ",");
			$mysql_command.= $thread_id_values;
			$mysql_command.=") GROUP BY `t1`.`thread_id` ORDER BY `t2`.`last_update` ASC
) AS `x` INNER JOIN `post` AS `p` ON `p`.`thread_id` = `x`.`thread_id` and `p`.`post_id` = `x`.`op_post_id` and
`posts_number`=`x`.`posts_number`;";
$stmt=$pdo->prepare($mysql_command);
$stmt->execute();
echo    "["; 
$response = "";
while ($row = $stmt->fetch()){
	if (file_exists("../content/".$row['thread_id']."/".$row['post_id'])){
			$attachment_type=1;
		}else{
			$attachment_type=0;
		}
		$text = str_replace('\\','\\\\',stripcslashes($row['post_text'])); 
		$text = str_replace("\"", "\\\"",$text); 
		$text = str_replace("\r\n", "\n",$text); 
		$text = str_replace("\r", "\n",$text); 
		$text = str_replace("\n", "\\n",$text);
		if (mb_strlen($text)>$maxPostLength)
			$text = mb_substr($text,0,$maxPostLength, 'UTF-8').'...';
		$response.="{\"id\":".$row['thread_id'].",".
		"\"postId\":".$row['post_id'].",".
		"\"timestamp\":".$row['ts'].",".
		"\"attachmentType\":".$attachment_type.",".
		"\"numberOfPosts\":".$row["posts_number"].",".
		"\"text\":\"".$text."\"".
		"},";
	 
}	
	$response= rtrim($response, ",");
	echo	$response;
	echo ']';	
}else{
	echo "[{".
	"\"id\":\"0\",".
	"\"postId\":\"0\",".
	"\"timestamp\":\"0\",".
	"\"attachmentType\":\"0\",".
	"\"numberOfPosts\":\"0\",".
	"\"text\":\"<em>Lol</em>, You are already subscribed to #twach.<span class='hiddenText'>Unsubscribe.</span>".
	"The thing is, this page shows You all the public threads <span class='hiddenText'> #twach makes a thread public</span>".
	"which did not make it to Your threads. And also excluding those, which You excluded. That is how it works.\"}]";
}
$stmt=null;
$pdo = null;
?>