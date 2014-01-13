<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Threads</title>
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
	switch($_COOKIE["layout"]){
		case 1:
		echo '<link type="text/css" rel="stylesheet" href="css/three-columns.css">';
		break;
		case 0:
		echo '<link type="text/css" rel="stylesheet" href="css/one-column.css">';
		break;	
	}
	?>
	<link rel='stylesheet' media='screen and (max-width: 970px)' href='css/main-mobile.css' />

</head>
<body>
	<nav>
		<span id="logo"><a href="threads.php"><image  src="images/giraffe.png"></a></span>
		<a href="#" id="settingsButton" onmouseover="this.querySelector('ul').style.display='block';" onmouseout="this.querySelector('ul').style.display='none';">
			<span class="glyphicon glyphicon-cog"> </span>  settings
			<ul class="settingsPanel">
				<li  id="layoutButton"><span class="glyphicon glyphicon-th"> </span>  layout</li>
				<li id="changeStyleButton"><span class="glyphicon glyphicon-adjust"></span> style</li>
			</ul></a>
			<a href="modifyTags.php"><span class="glyphicon glyphicon-tags"> </span>  tags</a>
	<!--a href="fundamentals.php"><span class="glyphicon glyphicon-question-sign"></span> fundamentals</a>
	<a href="donate.php"><span class="glyphicon glyphicon-wrench"></span> complain</a-->
		<a href="discover.php"><span class="glyphicon glyphicon-globe"></span> discover</a>
		<form action="search.php" method="get" class="searchElement">
			<input placeholder="tag" type="text" name="tag">
			<button><span class="glyphicon glyphicon-search"></span></button>
		</form>
	</nav>
	<div class="formOutterContainer">
		<form action="php/newThread.php" method="POST" class="newThreadForm postingForm" enctype="multipart/form-data" onsubmit="submitAttachments()">
			<div class="formContainer">
				<textarea name="post_text" placeholder="#Tag this post, otherwise nobody will ever read it!" required></textarea>
				<button type="submit">Post</button>
				<div class="buttonPanel">
					<button type="button" class="markupButton" title="ctrl+b" id="bold" href="#answerForm"><b>b</b></button>
					<button type="button" class="markupButton" title="ctrl+i" id="italics" href="#answerForm"><em>i</em></button>
					<button type="button" class="markupButton" title="ctrl+m" id="lined" href="#answerForm"><span class="linedText">l</span></button>
					<button type="button" class="markupButton" title="ctrl+[" id="hidden" href="#answerForm"><span class="hiddenText">h</span></button>
				</div>
				<div class="fileUpload">
					<input type="file" class="fileInput" name="post_pic">
					<button><span class="glyphicon glyphicon-picture"></span></button>
				</div>
				<input type="hidden" class="attachmentInput" name="attachment" value="">
				<div class="attachmentContainer"></div>
			</div>
		</form>
	</div>
	<div class="metaContainer">
		<div class="threadContainer" ></div>
		<?
		switch($_COOKIE["layout"]){
			case 1:
			echo '<div class="threadContainer" ></div>';
			echo '<div class="threadContainer" ></div>';
				//$maxPostLength = 140;
			break;

			case 0:
				//$maxPostLength = 440;
			break;
			default:
			echo '<div class="threadContainer" ></div>';
				//$maxPostLength = 240;
			break;

		}		
		?>

	</div>

	<script>
		
	</script>
	<footer class="threadFooter">
		<button class="pastThreadsButton">past</button>
	</footer>
	<script src="js/style.js"></script>
	<script src="js/layout.js"></script>
	<script src="js/markup.js"></script>
	<script src="js/attachment.js"></script>
	<script src="js/createThreadPreviewElement.js"></script>
	<script src="js/replaceMarkup.js"></script>
	<script src="js/loadNewThreads.js"></script>
</body>
</html>
