function makeNavigation(){
var currentPage = parseInt(window.location.href.split("=")[1].split("&")[0]);
var pageLink, 
containers = document.getElementsByClassName("threadContainer"),
numberOfThreadsOnPage= 0;
for (var i = containers.length - 1; i >= 0; i--) {
	numberOfThreadsOnPage+=containers[i].childNodes.length;
};
console.log(numberOfThreadsOnPage);
	if(numberOfThreadsOnPage==10){
	pageLink = document.createElement("a");
	pageLink.href="discover.php?offset="+(currentPage+1);
	pageLink.setAttribute("class","nextPage");
	var chervron = document.createElement("span");
	chervron.setAttribute("class","glyphicon glyphicon-chevron-right");
		pageLink.appendChild(chervron);
	var body = document.querySelector("body");
	body.appendChild(pageLink);

	}
	if(currentPage!=0){
		pageLink = document.createElement("a");
		pageLink.href="discover.php?offset="+(currentPage-1);
		pageLink.setAttribute("class","previousPage");
		var chervron = document.createElement("span");
		chervron.setAttribute("class","glyphicon glyphicon-chevron-left");
		pageLink.appendChild(chervron);
		var body = document.querySelector("body");
		body.appendChild(pageLink);
	}
}
