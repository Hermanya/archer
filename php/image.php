<?php
 
if (!empty($_GET["thread_id"])&&is_int((int)$_GET["thread_id"])&&
	!empty($_GET["post_id"])&&is_int((int)$_GET["post_id"])){
		$thread_id = $_GET["thread_id"];
		$post_id = $_GET["post_id"];
	}else{
		echo "you shouldn't be here";
	return;
}
$image_path = "../content/" . $thread_id . "/" .$post_id;
 $type=GetimageSize($image_path);
 $type = $type[2];
 switch ($type) {
	case 2:
		$image = ImageCreateFromJPEG($image_path);
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		break;
	case 1:
	case 0:
		header("Location:  ../content/$thread_id/$post_id");
		exit();
		$image = imagecreatefromgif($image_path);
		header('Content-Type: image/gif');
		imagegif($image);
		break;
	case 3:
		$image = imagecreatefrompng($image_path);
		header('Content-Type: image/png');
		imagepng($image);
		break;
	default:
		echo $type;
		echo "default";
		return;
		break;
		       	
}
	imagedestroy($image);
?>