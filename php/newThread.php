<?php 

require "../../conf.php";
require "MyDateTime.php";
/**
	Check all the input values
*/

	if (! empty($_POST['post_text'])&&is_string($_POST['post_text'])){
		$raw_text=$_POST['attachment'].$_POST['post_text'];
		$text=preg_replace("/\r\n|\n|\r/","<br>",htmlentities($raw_text, ENT_QUOTES, 'UTF-8'));
		//$text = preg_replace('#</a>#', "", $text);
		//$text = preg_replace("#<a class='answer' href='#", "", $text);
	}else{
		header('Location:  ../threads.php?offside=0&error=text');
		return;
	}

	$allowedExts = array("jpeg", "jpg", "png","JPEG", "JPG", "PNG","gif","GIF");
	$extension = end(explode(".", $_FILES["post_pic"]["name"]));
	if ($_FILES["post_pic"]["name"]!=null){
		if ((($_FILES["post_pic"]["type"] == "image/jpeg")
			|| ($_FILES["post_pic"]["type"] == "image/jpg")
			|| ($_FILES["post_pic"]["type"] == "image/gif")
			|| ($_FILES["post_pic"]["type"] == "image/png"))
			&& in_array($extension, $allowedExts))
		{
			if ($_FILES["post_pic"]["error"] > 0)
			{
				echo "Return Code: " . $_FILES["post_pic"]["error"] . "<br>";
				return;
			}
		}
		else
		{
			echo "Invalid file, ok?";
			return;
		}
		$image = $_FILES["post_pic"]["tmp_name"];
		$width=300; //*** Fix Width & Heigh (Autu caculate) ***//*
		$size=GetimageSize($image);
		if (($size[0]<$width)||
			(($size[1]/$size[0])>2)){
			echo "this is Invalid file ";
		return;
	}
}
/**
	Create the thread and op-post
*/
	$date = new MyDateTime();
	$mysql_command="INSERT INTO `thread` (`thread_id`,`last_update`) VALUES (NULL,".($date->getTimestamp()).")";
	$stmt=$pdo->prepare($mysql_command);
	$stmt->execute();
	$thread_id = $pdo->lastInsertId();


	preg_match_all('/(^|\s)\#([\pL_]+[-]?)*/u', $raw_text, $raw_matches);
	
	$matches = array();
	$upperMatches = array();
	$counter = 0;
	
	foreach ($raw_matches[0] as $key => $value) {
		$matches[$counter]=mb_substr(preg_replace( "/(^\s+)|(\s+$)/u", "", $value),1);
		$upperMatches[$counter]=mb_strtoupper(mb_substr(preg_replace( "/(^\s+)|(\s+$)/u", "", $value),1), 'UTF-8');
		$counter++;
	}

	if(count($matches)==0){
		$matches[0]="twach";
		$upperMatches[0]="TWACH";
		$text = "#twach, ".$text;
	}else{
		if(count($matches)>5){
			$matches = array_slice($matches, 0,4);
		}
	}
	$mysql_command="INSERT INTO `post` (`post_id`,`thread_id`,`post_text`,`remote_address`)
	VALUES (NULL,".$thread_id.",?,?)";
	$stmt=$pdo->prepare($mysql_command);
	$stmt->bindParam(1,$text);
	$stmt->bindParam(2,$_SERVER['REMOTE_ADDR']);

	$stmt->execute();

	$post_id = $pdo->lastInsertId();
	mkdir("../content/" . $thread_id, 0777);
	mkdir("../content/" . $thread_id."/resized", 0777);
	if ($_FILES["post_pic"]["name"]!=null){
		$pathToFullSize = "../content/" . $thread_id . "/" .$post_id;
		move_uploaded_file($image,$pathToFullSize);
		$height=round($width*$size[1]/$size[0]);

		switch ($size[2]) {
			case 2:
			$images_orig = ImageCreateFromJPEG($pathToFullSize);
			break;
			case 0:
			case 1:
			$images_orig = imagecreatefromgif($pathToFullSize);
			break;
			case 3:
			$images_orig = imagecreatefrompng($pathToFullSize);
			break;
			default:
			unlink($pathToFullSize);
			return;
			break;
		}
		if(! $images_orig){
			unlink($pathToFullSize);
			ImageDestroy($images_orig);
			echo "unlinked destroyed";
			return;
		}
		$photoX = ImagesX($images_orig);
		$photoY = ImagesY($images_orig);
		$images_fin = ImageCreateTrueColor($width, $height);
		ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
		switch ($size[2]) {
			case 2:
			ImageJPEG($images_fin,"../content/".$thread_id."/resized/".$post_id);
			break;
			case 0:
			case 1:
			imagegif($images_fin,"../content/".$thread_id."/resized/".$post_id);
			break;
			case 3:
			imagepng($images_fin,"../content/".$thread_id."/resized/".$post_id);
			break;
			default:
			echo "$size[2] contact administation";
			return;
			break;
		}
		ImageDestroy($images_orig);
		ImageDestroy($images_fin);
	}
/**
	Begin adding tags.
	Find them in the text and check, which ones already exist.
*/
	$matches = array_unique($matches);

	$mysql_command="SELECT `keyword` FROM `tag` WHERE UPPER(`keyword`) IN (";
		$tag_values = "";
		foreach ($upperMatches as $key => $value){
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
	var_dump($to_create_keys);
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
/**
	Finally, associate the tags with the thread.
*/
	$mysql_command="INSERT INTO `thread_tag` (`thread_id`,`tag_id`) VALUES ";
	$thread_tag_pairs = "";
	foreach ($tag_ids as $key => $value){
		$thread_tag_pairs.="(".$thread_id.",".$value[0]."),";
	}
	$thread_tag_pairs = rtrim($thread_tag_pairs, ",");
	$mysql_command.=$thread_tag_pairs;
	$mysql_command.=";";
	$stmt=$pdo->prepare($mysql_command);

	$stmt->execute();
/**
	And remove the most ancient thread
*/

	$mysql_command="SELECT `thread_id` FROM `thread` ORDER BY `last_update` ASC LIMIT 1;";
	$stmt=$pdo->prepare($mysql_command);
	$stmt->execute();
	$row = $stmt->fetch();
	$doomed_thread_id = $row["thread_id"];

	function rrmdir($dir) { 
		foreach(glob($dir . '/*') as $file) { 
			if(is_dir($file)) rrmdir($file); else unlink($file); 
		} rmdir($dir); 
	}
	if (is_dir("../content/".$doomed_thread_id)) {
		rrmdir("../content/".$doomed_thread_id);
	}
	$mysql_command="DELETE FROM `thread` WHERE `thread_id`=".$doomed_thread_id.";";
	$stmt=$pdo->prepare($mysql_command);
	$stmt->execute();
	$mysql_command="DELETE FROM `thread_tag` WHERE `thread_id`=".$doomed_thread_id.";";
	$stmt=$pdo->prepare($mysql_command);
	$stmt->execute();
	$mysql_command="DELETE FROM `post` WHERE `thread_id`=".$doomed_thread_id.";";
	$stmt=$pdo->prepare($mysql_command);
	$stmt->execute();

	$stmt=null;
	$pdo=null;
	header('Location:  ../thread.php?thread_id='.$thread_id);

	exit();
	?>




