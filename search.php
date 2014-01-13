<?php
if(! is_string($_GET["tag"]))
	return;
?>
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
			<a href="threads.php"><span class="glyphicon glyphicon-th-large"> </span>  threads</a>
			<a href="modifyTags.php"><span class="glyphicon glyphicon-tags"> </span>  tags</a>
	<a href="donate.php"><span class="glyphicon glyphicon-wrench"></span> complain</a-->
		<a href="discover.php"><span class="glyphicon glyphicon-globe"></span> discover</a>
		<form action="search.php" method="get" class="searchElement">
			<input type="hidden" value="0" name="offset">
			<input placeholder="tag" type="text" name="tag">
			<button><span class="glyphicon glyphicon-search"></span></button>
		</form>
	</nav>
	<div class="metaContainer">
		<div class="threadContainer" ></div>
		<?
		switch($_COOKIE["layout"]){
			case 1:
			echo '<div class="threadContainer" ></div>';
			echo '<div class="threadContainer" ></div>';
			break;

			case 0:
			break;
			default:
			echo '<div class="threadContainer" ></div>';
			break;

		}		
		?>

	</div>

	<script src="js/style.js"></script>
	<script src="js/layout.js"></script>
	<script src="js/createThreadPreviewElement.js"></script>
	<script src="js/replaceMarkup.js"></script>
	<footer class="threadFooter">
		<button class="pastThreadsButton">past</button>
	</footer>
	<script>
		var rehttp=new XMLHttpRequest(),reThttp=new XMLHttpRequest(), numberOfNewPosts=0;
		reThttp.onreadystatechange=function()
		{
			if (reThttp.readyState==4 && reThttp.status==200)
			{ 
				postsArray = JSON.parse(reThttp.responseText.split("<!--")[0]);
				for (var i = postsArray.length-1; i >=0 ; i--) {
					createThreadPreviewElement(postsArray[i]);
				};
				if (postsArray.length<10){
					document.querySelector(".pastThreadsButton").setAttribute("disabled","");
				}
			}
		}   
		rehttp.onreadystatechange=function()
		{
			if (rehttp.readyState==4 && rehttp.status==200)
			{ 
				reThttp.open("GET","api/getThreads.php?tag="+rehttp.responseText.split("<!--")[0],true);
				reThttp.send();
			}
		}            

		rehttp.open("GET","api/getTagIds.php?tag=<?=$_GET['tag']?>",true);
		rehttp.send();
		function loadNewThreads(){
			var containers = document.getElementsByClassName("threadContainer");
			var min = Number.POSITIVE_INFINITY;
			for (var i = containers.length - 1; i >= 0; i--) {
				if (min > containers[i].lastChild.getAttribute("timestamp"))
					min = containers[i].lastChild.getAttribute("timestamp");
			};
			rehttp.open("GET","api/getTagIds.php?tag=<?=$_GET['tag']?>&max="+min,true);
			rehttp.send();

		}
		document.querySelector(".pastThreadsButton").addEventListener("click",loadNewThreads,false);
	</script>
</body>
</html>
