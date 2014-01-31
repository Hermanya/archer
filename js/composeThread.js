function showComposeThread(){
	document.getElementsByClassName('newThreadOutterWrapper')[0].style.display='block';
}
document.getElementById('composeThread').addEventListener("click",showComposeThread,false);
function hideComposeThread(e){
	if (e.target==this)
	document.getElementsByClassName('newThreadOutterWrapper')[0].style.display='none';
}
document.getElementsByClassName("newThreadOutterWrapper")[0].addEventListener("click",hideComposeThread,false);