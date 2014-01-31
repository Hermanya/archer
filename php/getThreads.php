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
if ( isset($_GET['offset'])&&is_int((int)$_GET['offset'])){
	$offset = (int)$_GET['offset']*10;
	if ($offset<0) {
		$offset = 0;
	}
}else{
	return;
}
if (isset($_GET['tag'])){
	$userTags = explode(' ',$_GET['tag']);
}else{
	$userTags = explode(' ',$_COOKIE['tag']);
}
require "../../conf.php";

$mysql_command="SELECT DISTINCT `t2`.`thread_id`
FROM `thread_tag` AS `t1` 
JOIN `thread` AS `t2` ON `t1`.`thread_id` = `t2`.`thread_id`
WHERE `t1`.`tag_id` IN (";
	$tag_id_values = "";
	foreach ($userTags as $key => $value){
		$tag_id_values.=" ?,";
	}
	$tag_id_values = rtrim($tag_id_values, ",");
	$mysql_command.=$tag_id_values;
	$mysql_command.=") 
ORDER BY `t2`.`last_update` DESC LIMIT ".$offset.", 10;";
$stmt=$pdo->prepare($mysql_command);

$stmt->execute($userTags);
$thread_ids = $stmt->fetchAll();
if (count($thread_ids)!=0) {

	$mysql_command="SELECT `p`.`thread_id`,`p`.`post_id`,`p`.`post_text`,`p`.`attachment_type`, `posts_number`
	FROM  (
		SELECT `t1`.`thread_id`, MIN(`t1`.`post_id`) as `op_post_id`, COUNT(`t1`.`post_id`) as `posts_number`
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
	/*
	echo	'<a href="thread.php?thread_id='.$row['thread_id'].'" class="threadOpPost" ><div id="'.$row['thread_id'].'">';
	switch ($row["attachment_type"]) {
		case '1':
		echo "<image class='threadImage' src='content/".$row['thread_id']."/resized/".$row['post_id']."'>";
		break;
		default:
					# code...
		break;
	}
	echo 	'<span class="postAnswersNumber">';
	switch($row['posts_number']){
		case 1: break;
		default:
		echo ($row['posts_number']-1)." <span class=\"glyphicon glyphicon-comment\"></span>";
		break;
	}
	echo "</span>";
	$text = stripslashes($row['post_text']);
	if (mb_strlen($text)<$maxPostLength)
		echo 	'<p>'.$text.'</p>';
	else 
		echo 	'<p>'.mb_substr($text,0,$maxPostLength, 'UTF-8').'...</p>';
	echo	'</div></a>';*/
$text = str_replace('\\','\\\\',stripcslashes($row['post_text'])); 
$text = str_replace("\"", "\\\"",$text); 
$text = str_replace("\r\n", "\n",$text); 
$text = str_replace("\r", "\n",$text); 
$text = str_replace("\n", "\\n",$text);
if (mb_strlen($text)>$maxPostLength)
	$text = mb_substr($text,0,$maxPostLength, 'UTF-8').'...';
$response.="{\"id\":".$row['thread_id'].",".
	"\"attachmentType\":".$row["attachment_type"].",".
	"\"numberOfPosts\":".$row["posts_number"].",".
	"\"text\":\"".$text."\"".
"},";
} 
$response= rtrim($response, ",");
echo	$response;
echo ']';	
}else{
	echo "[{".
		"\"id\":".$row['thread_id'].",".
		"\"attachmentType\":".$row['thread_id'].",".
		"\"numberOfPosts\":0,".
		"\"text\":\"<em>Sigh</em>, it seems like there is nothing for You at the moment.<br>".
		"Well, start Your own discussion and don't forget to specify the tags, so those, who are simmilar".
		" to You could join Your discussion.<br><span class='hiddenText'>You won't regret.</span>\"}]";
}
$stmt=null;
$pdo = null;
?>