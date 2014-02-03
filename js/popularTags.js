function createPopularTagElement(obj){
	var element = document.createElement("div");
	element.setAttribute("class","popularTagElement");
	element.setAttribute("tagId",obj.tagId);
	//element.setAttribute("href","search.php?offset=0&tag="+obj.keyword);
	element.appendChild(document.createTextNode(obj.keyword));
	element.appendChild(createAddPopularTagButton());
	element.appendChild(createRemovePopularTagButton());
	document.querySelector(".popularTagsPanel").appendChild(element);

}
function addPopularTag(){
	var date = new Date();
      	date.setDate(date.getDate() + 365);
      	var attributes = document.cookie.split(";"); 
		var cookie = "{";
		for (var i = attributes.length - 1; i >= 0; i--) {
			cookie += "\""+attributes[i].split("=")[0].replace(/^\s\s*/, '').replace(/\s\s*$/, '')+
			"\":\""+attributes[i].split("=")[1].replace(/^\s\s*/, '').replace(/\s\s*$/, '')+"\",";
		};
		cookie = cookie.substr(0,cookie.length-1)+"}";
		cookie = JSON.parse(cookie);
		document.cookie = ["tag=",cookie.tag," ",this.parentNode.getAttribute("tagid"),"; expires=",date,"; path= /"].join("");
       document.location.href = "threads.php";
}
function removePopularTag(){
	//this.parentNode.parentNode.removeChild(this.parentNode);
	//loadNewPopularTag();
	var date = new Date();
      	date.setDate(date.getDate() + 365);
      	var attributes = document.cookie.split(";"); 
		var cookie = "{";
		for (var i = attributes.length - 1; i >= 0; i--) {
			cookie += "\""+attributes[i].split("=")[0].replace(/^\s\s*/, '').replace(/\s\s*$/, '')+
			"\":\""+attributes[i].split("=")[1].replace(/^\s\s*/, '').replace(/\s\s*$/, '')+"\",";
		};
		cookie = cookie.substr(0,cookie.length-1)+"}";
		cookie = JSON.parse(cookie);
		document.cookie = ["atag=",cookie.atag," ",this.parentNode.getAttribute("tagid"),"; expires=",date,"; path= /"].join("");
       document.location.href = "threads.php";
}
function createAddPopularTagButton(){
	var element = document.createElement("button");
	element.setAttribute("class","addPopularTagButton");
	element.addEventListener("click",addPopularTag,false);
	element.appendChild(document.createTextNode("+"));
	return element;
}
function createRemovePopularTagButton(){
	var element = document.createElement("button");
	element.setAttribute("class","removePopularTagButton");
	element.addEventListener("click",removePopularTag,false);
	element.appendChild(document.createTextNode(String.fromCharCode(215)));
	return element;
}
var pthttp=new XMLHttpRequest(), numberOfNewPosts=0;
		pthttp.onreadystatechange=function()
		{
			if (pthttp.readyState==4 && pthttp.status==200)
			{ 
				tagsArray = JSON.parse(pthttp.responseText.split("<!--")[0]);
				for (var i = tagsArray.length-1; i >=0 ; i--) {
					createPopularTagElement(tagsArray[i]);
				};
				if (tagsArray.length == 0)
					document.querySelector(".popularTagsPanel").style.display="none";

			}
		}                    
		pthttp.open("GET","api/getPopularTags.php?limit=10",true);
		pthttp.send();
function loadNewPopularTag(){
	pthttp.open("GET","api/getPopularTags.php",true);
	pthttp.send();

}
//document.querySelector(".pastThreadsButton").addEventListener("click",loadNewThreads,false);
