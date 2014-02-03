<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Threads</title>
	<link rel="shortcut icon" href="favicon.ico"> 
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="css/main.css">
	<link type="text/css" rel="stylesheet" href="css/popularTags.css">
	<?php
	switch($_COOKIE["layout"]){
		case 1:
		echo '<link type="text/css" rel="stylesheet" href="css/three-columns.css">';
		break;
		case 0:
		echo '<link type="text/css" rel="stylesheet" href="css/one-column.css">';
		break;	
	}
	switch($_COOKIE["style"]){
		case 1:
		echo '<link type="text/css" rel="stylesheet" href="css/main-1.css">';
		break;
		case 2:
		break;
		default:
		echo '<link type="text/css" rel="stylesheet" href="css/main-2.css">';
		echo '<script src="js/audio.js"></script>';
		break;	
	}
	?>
	<link rel='stylesheet' media='screen and (max-width: 600px)' href='css/main-mobile.css' />

</head>
<body>
<div class="topNavWrap">
	<nav class="topNav">
		<span id="logo"><a href="threads.php"><image  src="images/giraffe.jpg"></a></span>
		<button href="#" id="composeThread" title="Compose new thread" onclick="">
			new thread
		</button>
		<a href="#" id="settingsButton">
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
	</div> 
	<div class="metaContainer">
		<aside class="sideBar">
		<div class="newThreadOutterWrapper" onclick="">
				<div class="newThreadInnerWrapper">
					<div class="formOutterContainer">
						<form action="php/newThread.php" method="POST" class="newThreadForm postingForm" enctype="multipart/form-data" onsubmit="submitAttachments()">
							<div class="formContainer newThread">
								<textarea name="post_text" placeholder="#Tag this post, otherwise nobody will ever read it!" required></textarea>
								<button type="submit"><span style"width: 12px;height: 6px;">Post</span></button>
								<div class="buttonPanel">
									<button type="button" class="markupButton" title="ctrl+b" id="bold" href="#answerForm"><b>b</b></button>
									<button type="button" class="markupButton" title="ctrl+i" id="italics" href="#answerForm"><em>i</em></button>
									<button type="button" class="markupButton" title="ctrl+m" id="lined" href="#answerForm"><span class="linedText">l</span></button>
									<button type="button" class="markupButton" title="ctrl+[" id="hidden" href="#answerForm"><span class="hiddenText">h</span></button>
								</div>
					<!--div class="fileUpload">
						<input type="file" class="fileInput" name="post_pic">
						<button><span class="glyphicon glyphicon-picture"></span></button>
					</div-->
					<input type="hidden" class="attachmentInput" name="attachment" value="">
					<div class="attachmentContainer"></div>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="popularTagsPanel">
	<span>Popular tags:</span>
</div>
<div class="linksPanel">
			2013 <a href="https://twitter.com/Hermanhasawish" target="_blank">@Hermanhasawish</a> <a href="https://github.com/Hermanya/archer" target="_blank">GitHub</a>
		</div>
</aside>
<div class="threadContainer" ></div>
<?
switch($_COOKIE["layout"]){
	case 1:
	echo '<div class="threadContainer" ></div>';
	echo '<div class="threadContainer" ></div>';
	break;
	default:
	break;

}		
?>

</div>

<script>

</script>
<footer class="threadsFooter">
	<button class="pastThreadsButton">past</button>
</footer>
<script src="js/style.js"></script>
<script src="js/layout.js"></script>
<script src="js/markup.js"></script>
<script src="js/attachment.js"></script>
<script src="js/createThreadPreviewElement.js"></script>
<script src="js/replaceMarkupThreadPreview.js"></script>
<script src="js/loadNewThreads.js"></script>
<script src="js/popularTags.js"></script>
<script src="js/settingsButton.js"></script>
<script src="js/sideBarFix.js"></script>
<script src="js/composeThread.js"></script>
</body>
</html>
