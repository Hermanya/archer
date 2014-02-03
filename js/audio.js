var audio = document.createElement("audio");
window.addEventListener("keyup",keyupHandler,false);
function keyupHandler(){
	audio.setAttribute("src","../sound/k"+Math.floor(Math.random()*11+1)+".ogg");
	audio.play();
}
window.addEventListener("load",function(){
	audio.setAttribute("src","../sound/passgood.ogg");
	audio.play();
},false);