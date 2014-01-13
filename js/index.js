var existingTags=document.getElementsByClassName("existingTag");
/**
	Add a tag element to Your tags 
*/
 function addTagElement(tag){
 	var keywordInputs = document.getElementsByClassName("tagSpan");
	for (var i = keywordInputs.length - 1; i >= 0; i--) {
		if (tag==keywordInputs[i].innerHTML)
			return;
	};
	var container = document.getElementsByClassName("tagContainer")[0];
	var tagElement = document.createElement("div");
	var tagElementSpan = document.createElement("span");
	tagElementSpan.setAttribute("class","tagSpan");
	tagElementSpan.appendChild(document.createTextNode(tag));
	tagElement.appendChild(tagElementSpan);
	var tagElementRemoveButton = document.createElement("button");
	tagElementRemoveButton.innerHTML="<b>&times;</b>";
	tagElementRemoveButton.setAttribute("class","indexRoundButton");
	tagElementRemoveButton.addEventListener("click",removeTagElement,false);
	tagElement.appendChild(tagElementRemoveButton);
	tagElement.setAttribute("class","tagElement");
	container.insertBefore(tagElement,document.getElementById("addTagElement"));
 }
 function removeTagElement(){
	this.parentNode.parentNode.removeChild(this.parentNode);
 }
 /**
	Add entered tag
 */
function addMyTag(){
	addTagElement(document.getElementById("myTagInput").value);
	document.getElementById("myTagInput").value="";
}
function addExistingTag(){
	addTagElement(this.innerHTML);
	this.parentNode.removeChild(this);
}
var mentions = 0, maxNumberOfMentions = 0, k;
for (var i = existingTags.length - 1; i >= 0; i--) {
	existingTags[i].addEventListener("click",addExistingTag,false);
	mentions+=parseInt(existingTags[i].lang);
	if (parseInt(existingTags[i].lang)>maxNumberOfMentions)
		maxNumberOfMentions = parseInt(existingTags[i].lang);
};
mentions=mentions/existingTags.length;
for (var i = existingTags.length - 1; i >= 0; i--) {
	k = Math.floor(127-(parseInt(existingTags[i].lang)-mentions)/(maxNumberOfMentions-mentions)*127);
	existingTags[i].lang = k;
	existingTags[i].style.color="rgb("+k+", "+k+", "+k+")";
};

var addTagButton = document.getElementById("plusButton");
addTagButton.addEventListener("click",addMyTag,false);
var proceedButton = document.getElementById("proceed");
	var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function()
    {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
      {	
      	var date = new Date();
      	date.setDate(date.getDate() + 365)
      	document.cookie = "tag=" + escape(xmlhttp.responseText.split("<")[0]) +  
		"; expires=" + date.toUTCString() +"; path= /";
       document.location.href = "threads.php";
      }
    }
  /**
	Get tag Ids for the current set of user's tags
  */
function getTagIds(){
	var keywordInputs = document.getElementsByClassName("tagSpan");
	var string = "";
	for (var i = keywordInputs.length - 1; i >= 0; i--) {
		string += keywordInputs[i].innerHTML+" ";
	};
  xmlhttp.open("GET","api/getTagIds.php?tag="+string,true);
  xmlhttp.send();
}
proceedButton.addEventListener("click",getTagIds,false);
