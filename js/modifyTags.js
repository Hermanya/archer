
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

  xhttp.open("GET","api/getKeywords.php",true);
  xhttp.send();

//////////////////////////////////////
function toBottom(){
  this.removeEventListener("click",toBottom,false);
  this.firstChild.setAttribute("class","glyphicon glyphicon-chevron-right");
  document.getElementById("#interests").scrollIntoView();
  this.addEventListener("click",getTagIds,false);
}
proceedButton.addEventListener("click",toBottom,false);
window.onscroll = function(){
  proceedButton.firstChild.setAttribute("class","glyphicon glyphicon-chevron-right");
  proceedButton.removeEventListener("click",toBottom,false);
  proceedButton.addEventListener("click",getTagIds,false);
  
}