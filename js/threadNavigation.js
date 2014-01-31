function setNavigationPosition(){
var mainContainerWidth = document.querySelector(".metaContainer").offsetWidth;
var offset = (window.innerWidth - mainContainerWidth)/2-10;

document.querySelector(".threadNav").style.left=mainContainerWidth+offset+"px";
document.querySelector(".backToThreads").style.width=offset+"px";

};
setNavigationPosition();
window.addEventListener("resize",setNavigationPosition,false);