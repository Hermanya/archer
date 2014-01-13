<?php 
require "../../conf.php";
require "./replaceMarkup.php";
if ((! empty($_POST['post_text'])&&is_string($_POST['post_text']))||
	(! empty($_POST['attachment'])&&is_string($_POST['attachment']))){
		$text=nl2br(htmlentities($_POST['attachment'].$_POST['post_text'], ENT_QUOTES, 'UTF-8'),false);
}else{
	$text= " ";
}
$thread_id=(int)$_POST['thread_id'];
$mysql_command="SELECT COUNT(`post_id`) AS `Number of posts` FROM `post` WHERE `thread_id`=?;";
$stmt = $pdo->prepare($mysql_command);
$stmt->bindParam(1,$thread_id);
$stmt->execute();
$row = $stmt->fetch();
if ($row["Number of posts"]>500){
	$doomed_thread_id = $thread_id;
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

	header('Location:  ../threads.php?offset=0');
}else{
	if(empty($_FILES['file']['name'])){
	} else {
		$allowedExts = array("jpeg", "jpg", "png","JPEG", "JPG", "PNG","gif","GIF");
		$extension = end(explode(".", $_FILES["file"]["name"]));

		if ((($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/jpg")
		|| ($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/png"))
		&& in_array($extension, $allowedExts))
		  {
		  if ($_FILES["file"]["error"] > 0)
		    {
		    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
		    return;
		    }
		  }
		else
		  {
		  echo "Invalid file";
		  return;
		  }
		  $image = $_FILES["file"]["tmp_name"];
		$width=300; //*** Fix Width & Heigh (Autu caculate) ***//*
        $size=GetimageSize($image);
        if (($size[0]<$width)||
        	(($size[1]/$size[0])>2)){
        	echo "Invalid file";
		  return;
		}
	}


	$mysql_command="INSERT INTO `post` (`post_id`,`thread_id`,`post_text`,`remote_address`)
									VALUES (NULL,?,?,?)";
	$stmt = $pdo->prepare($mysql_command);
	$stmt->bindParam(1,$thread_id);
	$stmt->bindParam(2,$text);
	$stmt->bindParam(3,$_SERVER['REMOTE_ADDR']);

	$stmt->execute();

	$post_id = $pdo->lastInsertId();
	if(!empty($_FILES['file']['tmp_name'])){ 
			
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
		       		echo $size[2]." handles improperly, notify administration.";
		       		return;
		       	}
		       	if($size[0]<$width){
		       		copy($pathToFullSize,"../content/" . $thread_id . "/resized/" .$post_id);
		       		ImageDestroy($images_orig);
		       	}else{
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
		       			return;
		       			break;
		       	}
	        ImageDestroy($images_orig);
	        ImageDestroy($images_fin);
	    	}
	}

	$date = new DateTime();
	if(! isset($_POST["sage"])){
	$mysql_command="UPDATE `thread` SET `last_update`=".($date->getTimestamp())." WHERE `thread_id`=?;";
	$stmt=$pdo->prepare($mysql_command);
	$stmt->bindParam(1,$thread_id);
	$stmt->execute();
	}
	$stmt=null;
	$pdo=null;
	header('Location:  ../thread.php?thread_id='.$thread_id."#posted");
}
exit();
?>