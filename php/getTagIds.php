<?php
 if ( isset($_GET['tag'])&&is_string($_GET['tag'])){
		$raw_matches = explode(' ',$_GET['tag']);
		$raw_matches = array_unique($raw_matches);
}else{
	return;
}
require "../../conf.php";
$upperMatches = array();
$matches = array();
$counter = 0;

foreach ($raw_matches as $key => $value) {
	$upperMatches[$counter]=mb_strtoupper($value,'UTF-8');
	$matches[$counter]=$value;
	$counter++;
}
print_r($upperMatches);
$mysql_command="SELECT `keyword` FROM `tag` WHERE UPPER(`keyword`) IN (";
$tag_values = "";
foreach ($matches as $key => $value){
    	$tag_values.=" ?,";
        }
$tag_values = rtrim($tag_values, ",");
$mysql_command.=$tag_values;
$mysql_command.=");";
$stmt=$pdo->prepare($mysql_command);
$stmt->execute($upperMatches);
$raw_existing_keys = $stmt->fetchAll();

$existing_keys = array();
$counter = 0;
foreach ($raw_existing_keys as $key => $value) {
	$existing_keys[$counter] = $value["keyword"];
	$counter++;
}
/**
	Create those, which don't exist.
*/
	$counter = 0;
$to_create_keys = array();
foreach ($matches as $key1 => $value1) {
	$differs = true;
	foreach ($existing_keys as $key2 => $value2) {
		if(mb_strtoupper($value2, 'UTF-8') == mb_strtoupper($value1, 'UTF-8'))

		{
			$differs=false;
		}
	}
	if($differs){
	$to_create_keys[$counter]= $value1;
	$counter++;
	}
}

if(count($to_create_keys)!==0){
$mysql_command="INSERT INTO `tag` (`tag_id`,`keyword`) VALUES ";
$tag_values = "";
foreach ($to_create_keys as $key => $value){
    	$tag_values.="(NULL,?),";
        }
$tag_values = rtrim($tag_values, ",");
$mysql_command.=$tag_values;
$mysql_command.=";";
$stmt=$pdo->prepare($mysql_command);

$counter=0;
$indexed_to_create_keys = array();
foreach ($to_create_keys as $key => $value) {
	$indexed_to_create_keys[$counter]=$value;
	$counter++;
}

$stmt->execute($indexed_to_create_keys);
}
/**
	Select tags' ids
*/
$mysql_command="SELECT `tag_id` FROM `tag` WHERE upper(`keyword`) IN (";
$tag_values = "";
foreach ($matches as $key => $value){
    	$tag_values.="?,";
        }
$tag_values = rtrim($tag_values, ",");
$mysql_command.=$tag_values;
$mysql_command.=");";
$stmt=$pdo->prepare($mysql_command);

$stmt->execute($upperMatches);
$tag_ids = $stmt->fetchAll();
foreach ($tag_ids as $key => $value){
    	echo $value[0].' ' ;
        }


$stmt = null;
$pdo = null;

?>