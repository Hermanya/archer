<?php 
require "../../conf.php";
if (isset($_GET["limit"])){
	$limit = intval($_GET["limit"]);
}else{
	$limit = 1;
}
$userTags = explode(' ',$_COOKIE['tag']." ".$_COOKIE['atag']);
if (count($userTags)==0){
	$userTags = array_push($userTags, "138");
}
$mysql_command="SELECT `tag`.`keyword`, `tag`.`tag_id`, COUNT(`thread_tag`.`tag_id`) AS `numberOfMentions` 
				FROM `tag`
				INNER JOIN `thread_tag` 
				ON (`tag`.`tag_id`=`thread_tag`.`tag_id`) 
				WHERE `tag`.`tag_id` NOT IN (";
$mysql_command_params = "";
foreach ($userTags as $key => $value) {
	$mysql_command_params.="?,";
}
$mysql_command.=rtrim($mysql_command_params, ",");
$mysql_command.=")
				GROUP BY `tag`.`tag_id`
				ORDER BY `numberOfMentions` DESC LIMIT 0,".$limit.";";
$stmt=$pdo->prepare($mysql_command);
//echo $mysql_command;
$stmt->execute($userTags);
$response = "";
while ($row = $stmt->fetch()) {
	if(strlen($row["keyword"])!=0)
	$response.= "{".
//"\"numberOfMentions\":".$row["numberOfMentions"].",".
"\"tagId\":".$row["tag_id"].",".
"\"keyword\":\"".$row["keyword"]."\"},";
}
echo "[".rtrim($response, ",")."]";

$stmt = null;
$pdo = null;
?>