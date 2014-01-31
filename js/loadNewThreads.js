var rehttp=new XMLHttpRequest(), numberOfNewPosts=0;
		rehttp.onreadystatechange=function()
		{
			if (rehttp.readyState==4 && rehttp.status==200)
			{ 	console.log(rehttp.responseText.split("<!--")[0]);
				postsArray = JSON.parse(rehttp.responseText.split("<!--")[0]);
				for (var i = postsArray.length-1; i >=0 ; i--) {
					createThreadPreviewElement(postsArray[i]);
				};
				if (postsArray.length<10){
					document.querySelector(".pastThreadsButton").setAttribute("disabled","");
				}
			}
		}                    
		rehttp.open("GET","api/getThreads.php",true);
		rehttp.send();
function loadNewThreads(){
	var containers = document.getElementsByClassName("threadContainer");
	var min = Number.POSITIVE_INFINITY;
	for (var i = containers.length - 1; i >= 0; i--) {
		if (min > containers[i].lastChild.getAttribute("timestamp"))
			min = containers[i].lastChild.getAttribute("timestamp");
	};
	console.log("api/getThreads.php?max="+min);
	rehttp.open("GET","api/getThreads.php?max="+min,true);
	rehttp.send();

}
document.querySelector(".pastThreadsButton").addEventListener("click",loadNewThreads,false);