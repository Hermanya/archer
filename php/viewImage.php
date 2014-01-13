<?php ?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>thread</title>
	<link rel="shortcut icon" href="../favicon.ico"> 
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="../css/main.css">
	<?php
		switch($_COOKIE["style"]){
			case 1:
				echo '<link type="text/css" rel="stylesheet" href="../css/main-1.css">';
				break;
			case 2:
				echo '<link type="text/css" rel="stylesheet" href="../css/main-2.css">';
				break;	
		}
	?>
</head>
<body>
<div class="metaContainer">
	<img src="image.php?thread_id=<?=$_GET['thread_id']?>&post_id=<?=$_GET['post_id']?>">
</div>
<a class="backToThreads" onclick="window.history.back()"><span class="glyphicon glyphicon-chevron-left"></span></a>
</body>
</html>