<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>0 posts itt</title>
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
	<link rel='stylesheet' media='screen and (max-width: 970px)' href='css/main-mobile.css' />

</head>
<body>
	<nav id="top">
		<span id="logo"><a href="threads.php"><image  src="images/giraffe.png"></a></span>
		<a href="#" id="settingsButton" onmouseover="this.querySelector('ul').style.display='block';" onmouseout="this.querySelector('ul').style.display='none';">
			<span class="glyphicon glyphicon-cog"> </span>  settings
			<ul class="settingsPanel">
				<li id="changeStyleButton"><span class="glyphicon glyphicon-adjust"></span> style</li>
			</ul></a>
			<a href="threads.php"><span class="glyphicon glyphicon-th-large"> </span>  threads</a>
			<a href="modifyTags.php"><span class="glyphicon glyphicon-tags"> </span>  tags</a>
			<a href="discover.php"><span class="glyphicon glyphicon-globe"></span> discover</a>
			<form action="search.php" method="get" class="searchElement">
				<input placeholder="tag" type="text" name="tag">
				<button><span class="glyphicon glyphicon-search"></span></button>
			</form>
		</nav>
		<?php 
		require "../conf.php";

		if(isset($_GET['thread_id'])){
			$thread_id = intval($_GET['thread_id']);
			echo '<div class="metaContainer" id="'.$thread_id.'">';
		}else{
			echo 'bad try</body></html>';
			return;
		}

		$userTags = explode(' ',$_COOKIE['tag']);

		$mysql_command="SELECT DISTINCT `t2`.`thread_id` AS `next`".
		"FROM `thread_tag` AS `t1` ".
		"JOIN `thread` AS `t2` ON `t1`.`thread_id` = `t2`.`thread_id`".
		"WHERE `t2`.`last_update`<(".
			"SELECT `last_update`".
			"FROM `thread`".
			"WHERE `thread_id`= ".$thread_id.
			")". 
		"AND `t1`.`tag_id` IN (";
			$tag_id_values = "";
			foreach ($userTags as $key => $value){
				$tag_id_values.=" ?,";
			}
			$tag_id_values = rtrim($tag_id_values, ",");
			$mysql_command.=$tag_id_values;
			$mysql_command.=") ORDER BY `t2`.`last_update` DESC LIMIT 0,1;";
		$stmt=$pdo->prepare($mysql_command);
		$stmt->execute($userTags);
		$next_thread_id = $stmt->fetch();
		$next_thread_id = $next_thread_id[0];
?>
<a href="thread.php?thread_id=<?=$next_thread_id?>" class="nextPage"><span class="glyphicon glyphicon-chevron-right"></span></a>
<a class="backToThreads" href="<?=$_GET["d"]==1?'discover':'threads'?>.php"><span class="glyphicon glyphicon-chevron-left"></span></a>
</div>
<div class="formOutterContainer">
	<form action="php/newPost.php" id="answerForm" class="postingForm" method="POST" enctype="multipart/form-data" onsubmit="submitAttachments()">
		<div class="formContainer">
			<a class="rightToBottomLink" href="#bottom"><span class="glyphicon glyphicon-chevron-down"></span></a>
			<a class="leftToBottomLink" href="#top"><span class="glyphicon glyphicon-chevron-up"></span></a>

			<textarea name="post_text" placeholder="ctrl+enter to answer"></textarea><br>
			<button type="submit">Answer</button>
			<div class="buttonPanel">
				<button type="button" class="markupButton" title="ctrl+b" id="bold" href="#answerForm"><b>b</b></button>
				<button type="button" class="markupButton" title="ctrl+i" id="italics" href="#answerForm"><em>i</em></button>
				<button type="button" class="markupButton" title="ctrl+m" id="lined" href="#answerForm"><span class="linedText">l</span></button>
				<button type="button" class="markupButton" title="ctrl+[" id="hidden" href="#answerForm"><span class="hiddenText">h</span></button>
			</div>
			<div class="fileUpload">
				<input type="file" class="fileInput" name="file">
				<button><span class="glyphicon glyphicon-picture"></span></button>
			</div>
			<input type="checkbox" title="You won't bring the thread up" class="sage" name="sage"/>
			<input type="hidden" name="thread_id" value="<?=$thread_id?>" />
			<input type="hidden" class="attachmentInput" name="attachment" value="">
			<div class="attachmentContainer"></div>
		</div>
	</form>
</div>
<footer class="threadFooter" id="bottom">
	<button type="button" id="update"><span class="glyphicon glyphicon-refresh"> </span> update</button>
	<span>auto: <input type="checkbox" id="auto" /></span> 
</footer>
<script src="js/answer.js"></script>
<script src="js/createPostElement.js"></script>
<script src="js/replaceMarkup.js"></script>
<script src="js/markup.js"></script>
<script src="js/loadNewPosts.js"></script>
<script src="js/style.js"></script>
<script src="js/attachment.js"></script>
<script>


</script>
</body>
</html>
<?php 

$stmt=null;
$pdo=null;
return;
?>