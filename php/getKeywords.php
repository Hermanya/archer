<?php
 if ( ! empty($_COOKIE['tag'])){
		$matches = explode(' ',$_COOKIE['tag']);
}else{
	return;
}
require "../../conf.php";

$mysql_command="SELECT `keyword` FROM `tag` WHERE `tag_id` IN (";
$tag_values = "";
foreach ($matches as $key => $value){
    	$tag_values.="?,";
        }
$tag_values = rtrim($tag_values, ",");
$mysql_command.=$tag_values;
$mysql_command.=");";
$stmt=$pdo->prepare($mysql_command);

$stmt->execute($matches);
$tag_ids = $stmt->fetchAll();
foreach ($tag_ids as $key => $value){
    	echo $value[0]." ";
        }
$stmt = null;
$pdo = null;
?>