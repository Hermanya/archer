
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
	tagElementRemoveButton.setAttribute("class","indexRoundButton");
	tagElementRemoveButton.innerHTML="<b>&times;</b>";//appendChild(document.createTextNode("X"));
	tagElementRemoveButton.addEventListener("click",removeTagElement,false);
	tagElement.appendChild(tagElementRemoveButton);
	tagElement.setAttribute("class","tagElement");
	container.insertBefore(tagElement,document.getElementById("addTagElement"));
 }
 function removeTagElement(){
	this.parentNode.parentNode.removeChild(this.parentNode);
 }
function addMyTag(){
	addTagElement(document.getElementById("myTagInput").value);
	document.getElementById("myTagInput").value="";
}

var mentions = 0, maxNumberOfMentions = 0, k;


var addTagButton = document.getElementById("plusButton");
addTagButton.addEventListener("click",addMyTag,false);
var proceedButton = document.getElementById("proceed");
	var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function()
    {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
      {	
      	var date = new Date();
      	date.setDate(date.getDate() + 365);
		document.cookie = "atag= "+ escape(xmlhttp.responseText.split("<")[0]) +
		"; expires=" + date.toUTCString() +"; path= /";
       location.reload();
      }
    }
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
var xhttp=new XMLHttpRequest();
  xhttp.onreadystatechange=function()
    {
    if (xhttp.readyState==4 && xhttp.status==200)
      {
        var tags = xhttp.responseText.split("<")[0].split(" ");

		for (var i = tags.length - 2; i >= 0; i--) {
			addTagElement(tags[i]);
		};
      }
    }

  xhttp.open("GET","api/getExcludedKeywords.php",true);
  xhttp.send();