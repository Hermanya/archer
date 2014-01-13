document.getElementById("changeStyleButton").addEventListener("click",function(){
	var attributes = document.cookie.split(";"); 
	var cookie = "{";
	for (var i = attributes.length - 1; i >= 0; i--) {
		cookie += "\""+attributes[i].split("=")[0].replace(/^\s\s*/, '').replace(/\s\s*$/, '')+
		"\":\""+attributes[i].split("=")[1].replace(/^\s\s*/, '').replace(/\s\s*$/, '')+"\",";
	};
	cookie = cookie.substr(0,cookie.length-1)+"}";
	cookie = JSON.parse(cookie);
	var date = new Date();
	date = date.setDate(date.getDate() + 365);
	if (cookie.style==null){
      	document.cookie = "style=2; expires=" + date +"; path= /";
	}else{
		switch (cookie.style){
			case "1":
				document.cookie = "style=0; expires=" + date +"; path= /";
				break;
			case "0":
				document.cookie = "style=2; expires=" + date +"; path= /";
				break;
			default:
				document.cookie = "style=1; expires=" + date +"; path= /";
				break;
		}
	}
	location.reload();
},false);
