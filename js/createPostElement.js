function hiddenTextTouch(){
	this.style.backgroundColor="rgba(0,0,0,0)";
}
function showPostPreview(){
	if(removePreviewTimeout[removePreviewTimeout.length-1]!=null){
		window.clearTimeout(removePreviewTimeout[removePreviewTimeout.length-1]);
		removePreviewTimeout.splice(-1,1);
//		this.removeEventListener("mouseover",showPostPreview,false);
//		this.addEventListener("mouseout",removePostPreview,false);
return;
}
var previewId = this.href.split("#")[1];
var preview = document.getElementById(previewId).cloneNode(true);
preview.removeAttribute("id");
preview.setAttribute("class","post preview");
this.appendChild(preview);
if (this.getAttribute("class")!="answer")
	preview.getElementsByClassName("postAnswers")[0].style.display="block";
this.removeEventListener("mouseover",showPostPreview,false);
//	this.addEventListener("mouseout",removePostPreview,false);
var links = preview.getElementsByClassName("postAnswerLink");
for (var i = links.length - 1; i >= 0; i--) {
	links[i].addEventListener("mouseover",showPostPreview,false);
	if(links[i].childNodes.length!=1){
		links[i].removeChild(links[i].lastChild);
	}
};
}
function removePostPreview(){
	var list = traverseChildren(this);
	var e = event.toElement || event.relatedTarget;
	if (!!~list.indexOf(e)) {
		return;
	}
	var element = this;
	removePreviewTimeout[removePreviewTimeout.length] = window.setTimeout(function(){
		actuallyRemovePostPreview(element);
	},200);
//		element.addEventListener("mouseover",showPostPreview,false);
//		element.removeEventListener("mouseout",removePostPreview,false);
}
function actuallyRemovePostPreview(elem){
	window.clearTimeout(removePreviewTimeout[removePreviewTimeout.length-1]);
	removePreviewTimeout.splice(-1,1);
	if (elem.childNodes.length!=1)
		elem.removeChild(elem.lastChild);
	if(elem.parentNode.parentNode.id!=null){
		elem.addEventListener("mouseover",showPostPreview,false);
	}
} 
var _down=[],_up=[];
function animateHeightExpand(obj, height,scroll){
	var obj_height = obj.clientHeight;
	//console.log(scroll+":"+obj_height+"/"+height);
	if(obj_height >= height){
	/*	var diff = obj_height-height; 
		window.scrollBy(0,diff);
		obj.style.height = height + "px";*/
		return; 
	}else {
			if((obj_height + 8)<height){
			obj.style.height = (obj_height + 8) + "px";
			if(scroll){
				window.scrollBy(0,8);
			}
			}else{
				var differ =  height - obj_height;
				obj.style.height = (obj_height + differ) + "px";
				if(scroll){
					window.scrollBy(0,differ);
				}
			}
			setTimeout(function(){
				animateHeightExpand(obj, height,scroll);
			}, 10) 
		}
	}   
	function animateMarginExpand(obj,margin){
		margin+=2;
		if(margin >= 18){ 
			return; }
			else {
				obj.style.marginTop = margin + "px";
				obj.style.marginBottom = margin + "px";
				window.scrollBy(0,2);
				setTimeout(function(){
					animateMarginExpand(obj,margin);
				}, 20) 
			}
		}
		function animateMarginCollapse(obj,margin){
			margin-=2;
			if(margin == -2){ 
				
			//	obj.style.borderTopWidth=0;
			//	obj.style.borderBottomWidth=0;
				return; 
			}else{
						obj.style.marginTop = margin+"px";
						obj.style.marginBottom = margin+"px";
						window.scrollBy(0,-2);
										
					setTimeout(function(){
						animateMarginCollapse(obj,margin);
					}, 20) 
				}
			}
			function animateHeightCollapse(obj,height,scroll){
				var obj_height = obj.offsetHeight;
				if(obj_height == 0){ 
					if (scroll)
						actuallyRemoveAlternativePostPreview(obj.nextSibling,true);
					else
						actuallyRemoveAlternativePostPreview(obj.previousSibling,false);
					return; 
				}else {
					if((obj_height - 8)>0){
					obj.style.height = (obj_height - 8) + "px";
					if(scroll){
						window.scrollBy(0,-8);
					}
					}else{
						var differ = 8 - obj_height;
						obj.style.height = "0px";
						if(scroll){
							window.scrollBy(0,-obj_height);
						}
					}
					setTimeout(function(){
						animateHeightCollapse(obj,height,scroll);
					}, 10) 
				}
			}                                                        
			function showAlternativePostPreview(e){
				if((e.target!=this)&&(e.target.nodeName!="P")&&(e.target.nodeName!="SPAN"))
					return;
				if (getSelectionHtml().length!=0||e.ctrlKey)
					return;
				if(this.previousSibling!=null){
					this.previousSibling.style.borderBottomLeftRadius="4px";
					this.previousSibling.style.borderBottomRightRadius="4px";
				}
				if(this.nextSibling!=null){
					this.nextSibling.style.borderTopLeftRadius="4px";
					this.nextSibling.style.borderTopRightRadius="4px";
				}
				var expandedPost = document.createElement("div");
				expandedPost.setAttribute("class","expandedPost");
				window.scrollBy(0,1);
				this.parentNode.insertBefore(expandedPost,this);
				expandedPost.appendChild(this);

				this.removeEventListener('click',showAlternativePostPreview,false);
				this.addEventListener('click',removeAlternativePostPreview,false);
				var answersToElement = document.createElement("div");
				answersToElement.setAttribute("class","answersToContainer");
				this.parentNode.insertBefore(answersToElement,this);
				var answersFromElement = document.createElement("div");
				answersFromElement.setAttribute("class","answersFromContainer");
				this.parentNode.insertBefore(answersFromElement,this.nextSibling);
				var answerToLinks = this.querySelectorAll(".answer");
				var answerFromLinks = this.querySelectorAll(".postAnswerLink");
				var element,already=[];
				for (var i = 0; i < answerToLinks.length; i++) {
					element = document.getElementById(answerToLinks[i].href.split("#")[1]);
					if(element==null)
						continue;
					element = element.cloneNode(true);
					if(already.indexOf(element.getAttribute("id"))!=-1)
						continue;
					already[already.length]=element.getAttribute("id");
					element.removeAttribute("id");
					element.removeAttribute("class");
					answersToElement.appendChild(element);
				};
				already=[];
				for (var i = 0; i < answerFromLinks.length; i++) {
					element = document.getElementById(answerFromLinks[i].href.split("#")[1]);
					if(element==null)
						continue;
					element = element.cloneNode(true);
					if(already.indexOf(element.getAttribute("id"))!=-1)
						continue;
					already[already.length]=element.getAttribute("id");
					element.removeAttribute("id");
					element.removeAttribute("class");
					answersFromElement.appendChild(element);
				};
	var max = answersToElement.offsetHeight;
	answersToElement.style.height="0px";
	animateHeightExpand(answersToElement, max,true);
	max = answersFromElement.offsetHeight;
	answersFromElement.style.height="0px";
	animateHeightExpand(answersFromElement, max,false);
	animateMarginExpand(expandedPost,0);
}
function removeAlternativePostPreview(e){
	if((e.target!=this)&&(e.target.nodeName!="P")&&(e.target.nodeName!="SPAN"))
		return;
	if (getSelectionHtml().length!=0)
		return;
	if(this.parentNode.previousSibling!=null){
		this.parentNode.previousSibling.style.borderBottomLeftRadius="0";
		this.parentNode.previousSibling.style.borderBottomRightRadius="0";
	}
	if(this.parentNode.nextSibling!=null){
		this.parentNode.nextSibling.style.borderTopLeftRadius="0";
		this.parentNode.nextSibling.style.borderTopRightRadius="0";
	}
	//window.scrollBy(0,-this.previousSibling.clientHeight);
	animateHeightCollapse(this.previousSibling,this.previousSibling.clientHeight,true);
	animateHeightCollapse(this.nextSibling,this.nextSibling.clientHeight,false);
}
function actuallyRemoveAlternativePostPreview(elem,isAnswersTo){
	if (isAnswersTo)
		elem.parentNode.removeChild(elem.previousSibling);
	else
		elem.parentNode.removeChild(elem.nextSibling);
	if(elem.parentNode.childNodes.length==1){
		animateMarginCollapse(elem.parentNode,16);
		window.setTimeout(function(){
		elem.removeEventListener('click',removeAlternativePostPreview,false);
		elem.addEventListener('click',showAlternativePostPreview,false);
		elem.parentNode.parentNode.insertBefore(elem,elem.parentNode);
		elem.parentNode.removeChild(elem.nextSibling);
		window.scrollBy(0,-1);
	},280);
	}
}

var removePreviewTimeout=[];
//quick and dirty BFS children traversal, Im sure you could find a better one                                        
function traverseChildren(elem){
	var children = [];
	var q = [];
	q.push(elem);
	while (q.length>0)
	{
		var elem = q.pop();
		children.push(elem);
		pushAll(elem.children);
	}
	function pushAll(elemArray){
		for(var i=0;i<elemArray.length;i++)
		{
			q.push(elemArray[i]);
		}

	}
	return children;
}
function humanTiming(stamp){
	difference = (new Date().getTime())- Math.round(stamp*1000);
	var tokens = {
		"year":31536000000,
		"month":2592000000,
		"week":604800000,
		"day":86400000,
		"h":3600000,
		"m":60000,
		"s":1000,
		"now":0
	};
	for (var key in tokens) {
		var value = tokens[key];
		if (difference<value) continue;
		switch(key){
			case "h":
			case "m":
			case "s":
			return [Math.floor(difference/value),key].join("");
			break;
			case "now":
			return "now";
			default:
			var d = new Date();
			d.setTime(Math.round(stamp*1000));
			return d.toLocaleDateString();
		}
	};
}
function makeFullImage(){
	//this.removeEventListener("click",makeFullImage,false);
	var element = document.createElement("img");
	element.addEventListener("click",makeImagePreview,false);
	element.setAttribute("class","postImage fullSizeImage");
	element.setAttribute("src","php/image.php?thread_id="+threadId+"&post_id="+this.parentNode.id);
	this.parentNode.insertBefore(element,this);
	this.parentNode.removeChild(this);
}
function makeImagePreview(){
	//this.removeEventListener("click",makeImagePreview,false);
	var element = document.createElement("div");
	element.addEventListener("click",makeFullImage,false);
	element.style.background="url('content/"+threadId+"/resized/"+this.parentNode.id+"') 50% 50% no-repeat";
	element.setAttribute("class","postImage");
	this.parentNode.insertBefore(element,this);
	this.parentNode.removeChild(this);
}
function createPostElement(postObject){
	var divPost = document.createElement("div");
	divPost.addEventListener("click",showAlternativePostPreview,false);
	divPost.setAttribute("class","post");
	divPost.setAttribute("id",postObject.id);
	/*if (!( 'ontouchstart' in window || 'onmsgesture'in window)){
			divPost.addEventListener("click",answer,false);

		}*/
		var element;
		switch(postObject.attachmentType){
			case 1:
			element = document.createElement("div");
			element.style.background="url('content/"+threadId+"/resized/"+postObject.id+"') 50% 50% no-repeat";
			element.setAttribute("class","postImage");
			element.addEventListener("click",makeFullImage,false);
			divPost.appendChild(element);
			break;
			default:
			break;
		}

		element = document.createElement("a");
		element.setAttribute("class","timestamp");
		element.setAttribute("ts",postObject.timestamp);
		element.appendChild(document.createTextNode(humanTiming(postObject.timestamp) ) );
		divPost.appendChild(element);
		element = document.createElement("a");
		element.setAttribute("class","postAnswersNumber");
		divPost.appendChild(element);
		element = document.createElement("a");
		element.setAttribute("class","postId");
		if ( 'ontouchstart' in window || 'onmsgesture'in window){
			element.addEventListener("touchstart",answer,false);
		}else{
			element.addEventListener("mousedown",answer,false);

		}
		element.appendChild(document.createTextNode(String.fromCharCode(65119)+postObject.id));
		divPost.appendChild(element);
		element = document.createElement("p");
		if (!( 'ontouchstart' in window || 'onmsgesture'in window)){
			element.addEventListener("mouseup",answer,false);
		}
		postObject.text=replaceMarkup(postObject.text);
		element.innerHTML=postObject.text;
		divPost.appendChild(element);
		answers = element.getElementsByClassName("answer");
		posts = document.getElementsByClassName("post");
		for (var i = 0; i < answers.length ; i++) {
			answers[i].addEventListener("mouseover",showPostPreview,false);
			answers[i].addEventListener("mouseout",removePostPreview,false);
			postId = parseInt(answers[i].href.split("#")[1]);
			var parent = answers[i].parentNode.parentNode;
			while (parent.id==null){
				parent = parent.parentNode;
			}
			answerId = parent.id;
			answeredPost = document.getElementById(postId);
			if (answeredPost==null)
				continue;
			var answerLink = document.createElement("a");
			answerLink.setAttribute("href","#"+answerId);
			answerLink.setAttribute("class","postAnswerLink");
			answerLink.appendChild(document.createTextNode(String.fromCharCode(187)+answerId));
			var postAnswersArray = answeredPost.getElementsByClassName("postAnswers")[0];
			postAnswersArray.appendChild(answerLink);
			answerLink.addEventListener("mouseover",showPostPreview,false);
			answerLink.addEventListener("mouseout",removePostPreview,false);
			var postAnswersNumberElement = answeredPost.getElementsByClassName("postAnswersNumber")[0];
			if(postAnswersArray.childNodes.length==1){
				postAnswersNumberElement.appendChild(
					document.createTextNode("1 "+String.fromCharCode(171))
					);
				postAnswersNumberElement.addEventListener("click",function(){
					var div = this.parentNode.getElementsByClassName("postAnswers")[0];
					if(div.style.display=="block"){
						div.style.display="none";
					}else{
						div.style.display="block";
					}
				},false);
			}else{
				postAnswersNumberElement.firstChild.nodeValue=postAnswersArray.childNodes.length+" "+String.fromCharCode(171);
			}
		};

		element = document.createElement("div");
		element.setAttribute("class","postAnswers");
		divPost.appendChild(element);
		var form = document.getElementsByClassName("formOutterContainer")[0];
		document.getElementsByClassName('metaContainer')[0].insertBefore(divPost,form);
		if ( 'ontouchstart' in window || 'onmsgesture'in window){
			var hiddenText = divPost.getElementsByClassName("hiddenText");
			for (var i = hiddenText.length - 1; i >= 0; i--) {
				hiddenText[i].addEventListener("touchstart",hiddenTextTouch,false);
			};
		}
	}
