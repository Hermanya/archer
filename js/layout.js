document.getElementById("layoutButton").addEventListener("click",function(){
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
	if (cookie.layout==null){
      	document.cookie = "layout=1; expires=" + date +"; path= /";
	}else{
		switch (cookie.layout){
			case "1":
				document.cookie = "layout=2; expires=" + date +"; path= /";
				break;
			case "2":
				document.cookie = "layout=0; expires=" + date +"; path= /";
				break;
			default:
				document.cookie = "layout=1; expires=" + date +"; path= /";
				break;
		}
	}
	location.reload();
},false);