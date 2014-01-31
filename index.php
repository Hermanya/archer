<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<script>
		if(navigator.cookieEnabled)
	  {
		 if(document.cookie.split("=").length>1)
		 	document.location.href = "threads.php?offset=0";
	  }
	else
	  {
	  	window.location.assign("http://www.google.com/chrome");
	  }
	</script>
	<title>Non-hierarchical anonymous Twach</title>
	<link rel="shortcut icon" href="images/favicon.png"> 
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="css/main.css">
	<?php
		if($_COOKIE["style"]==1)
			echo '<link type="text/css" rel="stylesheet" href="css/main-1.css">';	
	?>
	<link type="text/css" rel="stylesheet" href="css/modify.css">
	<link type="text/css" rel="stylesheet" href="css/index.css">
</head>
<body>
<nav class="topNav">
	<span id="welcomeMessage">Welcome to an anonymous forum!</span>
</nav>
<div class="container">
<iframe src="//player.vimeo.com/video/79016440?title=0&amp;byline=0&amp;portrait=0&amp;color=41a38b&amp;autoplay=0" class="welcomeVideo" width="837" height="470" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
<h2 id="interests">What are you interested in?</h2>
<div class="tagContainer">
	<div class ="addTagElement">
		<input type="text" id="myTagInput" />
		<button id="plusButton" class="indexRoundButton">+</button>
		<button id="proceed" ><span class="glyphicon glyphicon-ok"></span></button>
	</div>
	<br>
</div>
<div id="existingTagContainer">
<?php 
require "../conf.php";

//$mysql_command="SELECT `keyword` FROM `tag` LIMIT 200;";
$mysql_command="SELECT `tag`.`keyword`, COUNT(`thread_tag`.`tag_id`) AS `numberOfMentions` FROM `tag`
				LEFT JOIN `thread_tag` ON (`tag`.`tag_id`=`thread_tag`.`tag_id`) GROUP BY `tag`.`tag_id`
				 ORDER BY `numberOfMentions` DESC LIMIT 19;";
$stmt=$pdo->prepare($mysql_command);
$stmt->execute();
while ($row = $stmt->fetch()) {
	if(strlen($row["keyword"])!=0)
	echo "<div class='existingTag' lang='".$row["numberOfMentions"]."'>".$row["keyword"]."</div>";
}
$stmt = null;
$pdo = null;
?>
</div>
<footer>
	<p>2013, Herman</p>
</footer>
</div>
<script src="js/index.js"></script>
<script src="js/modify.js"></script>
<script>
	addTagElement("twach");
	addTagElement("твач");
</script>

</body>
</html>
