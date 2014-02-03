	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title>Threads</title>
		<link rel="shortcut icon" href="favicon.ico"> 
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
		<link type="text/css" rel="stylesheet" href="css/main.css">
		<link type="text/css" rel="stylesheet" href="css/index.css">
		<?php
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
			switch($_COOKIE["layout"]){
				case 0:
					echo '<link type="text/css" rel="stylesheet" href="css/one-column.css">';
					break;	
			}		
		?>
		<link rel='stylesheet' media='screen and (max-width: 600px)' href='css/main-mobile.css' />
	</head>
	<body>
	<div class="topNavWrap">
	<nav class="topNav">
		<span id="logo"><a href="threads.php"><image  src="images/giraffe.jpg"></a></span>
					<a href="#" id="settingsButton" >
			<span class="glyphicon glyphicon-cog"> </span>  settings
				<ul class="settingsPanel">
				<li  id="layoutButton"><span class="glyphicon glyphicon-th"> </span>  layout</li>
				<li id="changeStyleButton"><span class="glyphicon glyphicon-adjust"></span> style</li>
				</ul></a>
		<a href="modifyTags.php"><span class="glyphicon glyphicon-tags"> </span>  tags</a>
		<!--a href="fundamentals.php"><span class="glyphicon glyphicon-question-sign"></span> fundamentals</a>
		<a href="donate.php"><span class="glyphicon glyphicon-wrench"></span> complain</a-->
		<a href="threads.php"><span class="glyphicon glyphicon-th-large"></span> threads</a>
		<form action="search.php" method="get" class="searchElement">
					<input placeholder="tag" type="text" name="tag">
					<button><span class="glyphicon glyphicon-search"></span></button>
					</form>
	</nav>
	</div>
	<!--form action="php/newThread.php" method="POST" id="newThreadForm" enctype="multipart/form-data">
		<div class="buttonPanel">
			<a class="markupButton" id="bold" href="#answerForm"><b>b</b></a>
			<a class="markupButton" id="italics" href="#answerForm"><em>i</em></a>
			<a class="markupButton" id="lined" href="#answerForm"><span class="linedText">l</span></a>
			<a class="markupButton" id="hidden" href="#answerForm"><span class="hiddenText">h</span></a>
			<a class="markupButton" id="quot" href="#answerForm"><span class="quot">&gt;</span></a>
		</div>
		<textarea name="post_text" rows="4" cols="57" placeholder="#Tag this post, otherwise nobody will ever read it!" required></textarea>
		<input type="file" name="post_pic" required>
		<button type="submit">Post</button>
	</form-->
	<div class="metaContainer">
	<aside class="sideBar">
		<div class="tagContainer">
			<h3 id="interests">Excluding:</h3>
			<div class ="addTagElement">
				<input type="text" id="myTagInput" />
				<button id="plusButton" class="indexRoundButton">+</button>
				<button id="proceed" ><span class="glyphicon glyphicon-ok"></span></button>
			</div>
			<br>
		</div>
		<div class="linksPanel">
			2013 <a href="https://twitter.com/Hermanhasawish" target="_blank">@Hermanhasawish</a> <a href="https://github.com/Hermanya/archer" target="_blank">GitHub</a>
		</div>
	</aside>
	<div class="threadContainer" >
		</div>
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
	<footer class="threadsFooter">
		<button class="pastThreadsButton">past</button>
	</footer>
	<script src="js/style.js"></script>
	<script src="js/layout.js"></script>
	<script src="js/discovery.js"></script>
	<script src="js/createThreadPreviewElement.js"></script>
	<script src="js/replaceMarkupThreadPreview.js"></script>
	<script src="js/settingsButton.js"></script>
	<script src="js/sideBarFix.js"></script>
	
	<script>
		var rehttp=new XMLHttpRequest(), numberOfNewPosts=0;
		rehttp.onreadystatechange=function()
		{
			if (rehttp.readyState==4 && rehttp.status==200)
			{ 
				postsArray = JSON.parse(rehttp.responseText.split("<!--")[0]);
				for (var i = postsArray.length - 1; i >= 0; i--) {
					postsArray[i].discover = 1;
				};
				for (var i = postsArray.length-1; i >=0 ; i--) {
					createThreadPreviewElement(postsArray[i]);
				};
				if (postsArray.length<10){
					document.querySelector(".pastThreadsButton").setAttribute("disabled","");
				}
			}
		}                    
		rehttp.open("GET","api/getDiscoveredThreads.php",true);
		rehttp.send();
function loadNewThreads(){
	var containers = document.getElementsByClassName("threadContainer");
	var min = Number.POSITIVE_INFINITY;
	for (var i = containers.length - 1; i >= 0; i--) {
		if (min > containers[i].lastChild.getAttribute("timestamp"))
			min = containers[i].lastChild.getAttribute("timestamp");
	};
	rehttp.open("GET","api/getDiscoveredThreads.php?max="+min,true);
	rehttp.send();

}
document.querySelector(".pastThreadsButton").addEventListener("click",loadNewThreads,false);
	</script>
	</body>
	</html>
