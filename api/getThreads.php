<?php 
require '../php/MyDateTime.php';
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
$date = new MyDateTime();
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
if (isset($_GET['tag'])){
	$userTags = explode(' ',$_GET['tag']);
}else{

	$userTags = explode(' ',$_COOKIE['tag']);
}
require "../../conf.php";
$mysql_command="SELECT DISTINCT `t2`.`thread_id`,`t2`.`last_update`
FROM `thread_tag` AS `t1` 
JOIN `thread` AS `t2` ON `t1`.`thread_id` = `t2`.`thread_id`
WHERE `t2`.`last_update`<".$last_update." AND `t1`.`tag_id` IN (";
	$tag_id_values = "";
	foreach ($userTags as $key => $value){
		$tag_id_values.=" ?,";
	}
	$tag_id_values = rtrim($tag_id_values, ",");
	$mysql_command.=$tag_id_values;
	$mysql_command.=")
ORDER BY `t2`.`last_update` DESC LIMIT 0, ".$limit.";";
$stmt=$pdo->prepare($mysql_command);

$stmt->execute($userTags);
$thread_ids = $stmt->fetchAll();
if (count($thread_ids)!=0) {

	$mysql_command="SELECT `p`.`thread_id`,`p`.`post_id`,`p`.`post_text`, `posts_number`,`ts`
	FROM  (
		SELECT `t1`.`thread_id`, MIN(`t1`.`post_id`) as `op_post_id`, 
				COUNT(`t1`.`post_id`) as `posts_number`,`t2`.`last_update` as `ts`
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
	"\"lastUpdate\":".$row['ts'].",".
	"\"attachmentType\":0,".//.$attachment_type.",".
	"\"numberOfPosts\":".$row["posts_number"].",".
	"\"text\":\"".$text."\"".
	"},";
} 
$response= rtrim($response, ",");
echo	$response;
echo ']';	
}else{
	echo "[{".
	"\"id\":0,".
	"\"postId\":0,".
	"\"lastUpdate\":0,".
	"\"attachmentType\":0,".
	"\"numberOfPosts\":0,".
	"\"text\":\"<em>Sigh</em>, it seems like there is nothing for You at the moment.<br>".
	"Well, start Your own discussion and don't forget to specify the tags, so those, who are simmilar".
	" to You could join Your discussion.<br><span class='hiddenText'>You won't regret.</span>\"}]";
}
$stmt=null;
$pdo = null;
?>