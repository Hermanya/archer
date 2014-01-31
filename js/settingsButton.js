function turnSettingsButton(){
	if (this.getAttribute("on")=="true"){
		this.setAttribute("on","false");
		this.querySelector('ul').style.display='none';
	}else{
		this.setAttribute("on","true");
		this.querySelector('ul').style.display='block';
	}
}
document.getElementById("settingsButton").addEventListener("click",turnSettingsButton,false);