<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Tags</title>
	<link rel="shortcut icon" href="favicon.ico"> 
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="css/main.css">
	<?php
		switch($_COOKIE["style"]){
			case 1:
				echo '<link type="text/css" rel="stylesheet" href="css/main-1.css">';
				break;
			case 2:
				echo '<link type="text/css" rel="stylesheet" href="css/main-2.css">';
				break;	
		}
	?>
	<link type="text/css" rel="stylesheet" href="css/index.css">
	<link type="text/css" rel="stylesheet" href="css/modify.css">
	<link rel='stylesheet' media='screen and (max-width: 600px)' href='css/main-mobile.css' />
</head>
<body>
<nav class="topNav">
	<span id="welcomeMessage">What are You interested in?:</span>
</nav>
<div class="container">
	
<div id="existingTagContainer">
<?php 
require "../conf.php";

$mysql_command="SELECT `tag`.`keyword`, COUNT(`thread_tag`.`tag_id`) AS `numberOfMentions` 
				FROM `tag`
				LEFT JOIN `thread_tag` 
				ON (`tag`.`tag_id`=`thread_tag`.`tag_id`) 
				GROUP BY  `tag`.`tag_id` 
				ORDER BY `numberOfMentions` DESC LIMIT 200;";
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
<div class="tagContainer">
	<h3 class="addTagElement">My interests:</h3>
	<div class="addTagElement">
		<input type="text" id="myTagInput"/>
		<button id="plusButton" class="indexRoundButton">+</button>
		<button id="proceed"><span class="glyphicon glyphicon-ok"></button>
	</div>
</div>
<footer> </footer>
</div>
<script src="js/index.js"></script>
<script src="js/modifyTags.js"></script>
</body>
</html>