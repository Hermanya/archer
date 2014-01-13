
function createThreadPreviewElement(obj){
	var element = document.createElement("a");
	element.setAttribute("class","threadOpPost");
	element.setAttribute("timestamp",obj.lastUpdate);
	if (obj.discover!=null)
	element.setAttribute("href","thread.php?thread_id="+obj.id+"&d=1");
	else
	element.setAttribute("href","thread.php?thread_id="+obj.id);
	var parent = getParentContainer();
	parent.appendChild(element);
	var divPost;

	divPost = document.createElement("div");
	divPost.setAttribute("id",obj.id);
	element.appendChild(divPost);
	switch(obj.attachmentType){
		case 1:
		/*
		element = document.createElement("img");
		element.setAttribute("class","threadImage");
		element.setAttribute("src","content/"+obj.id+"/resized/"+obj.postId);
		divPost.appendChild(element);*/
		
		element = document.createElement("div");
		element.style.background="url('content/"+obj.id+"/resized/"+obj.postId+"') 50% 50% no-repeat";
		element.setAttribute("class","threadImage");
		divPost.appendChild(element);
		break;
		default:
		break;
	}
	var pelement = document.createElement("p");
	obj.text=replaceMarkup(obj.text);
	pelement.innerHTML = obj.text;
	divPost.appendChild(pelement);
	if(obj.attachmentType==0){
		if(pelement.querySelector("img")){
			var previewImage = document.createElement("div");
			previewImage.setAttribute("class","threadImage");
			previewImage.style.background="url('"+pelement.querySelector("img").getAttribute("src")+"') 50% 50% no-repeat";
			divPost.insertBefore(previewImage,pelement);
			var img = pelement.querySelector("img");
			pelement.removeChild(img.parentNode);
		}else{
			pelement.style.marginTop="1em";
		}
	}
		element = document.createElement("span");
		element.setAttribute("class","postAnswersNumber");
		if (obj.numberOfPosts!=1){
			element.appendChild(document.createTextNode(obj.numberOfPosts-1+" "));
			var span = document.createElement("span");
			span.setAttribute("class","glyphicon glyphicon-comment");
			element.appendChild(span);
		}
		pelement.appendChild(element);
	}
	function getParentContainer(){
		var containers = document.getElementsByClassName("threadContainer");
		var parent = containers[0];
			for (var j = 0; j < containers.length; j++) {
				if (containers[j].scrollHeight < parent.scrollHeight){
					parent = containers[j];
				}
			};
			return parent;
};
