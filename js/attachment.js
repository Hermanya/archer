var textarea = document.getElementsByTagName("textarea")[0];
textarea.addEventListener("paste",setPasteTimeout,false);
function setPasteTimeout(){
	window.setTimeout(addAttachment,10);
}
function createRemoveAttachmentButton(){
	var element = document.createElement("button");
    element.setAttribute("class","removeAttachmentElementButton");
    element.setAttribute("type","button");
    element.appendChild(document.createTextNode(String.fromCharCode(215)));
    element.addEventListener("click",removeAttachmentElement,false);
    return element;
}
function removeAttachmentElement(){
    if(this.parentNode.title=="")
        document.querySelector(".fileInput").value="";
	document.querySelector(".attachmentContainer").removeChild(this.parentNode);
}
function addAttachment(){
   var attachmentContainer = document.querySelector(".attachmentContainer");
   textarea.value = textarea.value.replace(/(?:https?:\/\/\S+)|(?:www.\S+)/g,function(match){
    if(match.indexOf('http://') !== 0 && match.indexOf('https://') !== 0)
    {
        match = 'http://' + match;
    }
    var parser = document.createElement('a');document.querySelector(".fileInput").value="";
    console.log()
    parser.href =   match;

                // images
                if(parser.pathname.match(/\.(png|jpg|gif|PNG|JPG|GIF)$/))
                {	
                	var attachmentElement = document.createElement("div");
                	attachmentContainer.appendChild(attachmentElement);
                	attachmentElement.setAttribute("title",match);
                	attachmentElement.setAttribute("class","attachmentElement");
                	attachmentElement.style.background="url('"+match+"') 50% 50% no-repeat";
                	attachmentElement.appendChild(createRemoveAttachmentButton());
                    return '';
                }
                // youtube
                if((parser.hostname.indexOf('www.youtube.com')==0
                  || parser.hostname.indexOf('youtube.com')==0)
                  && parser.pathname == '/watch'
                  && parser.search!=null)
                {
                    var parameters = parser.search.substring(1).split("&"),vid;
                    for (var i = parameters.length - 1; i >= 0; i--) {
                        if (parameters[i].split("=")[0]=="v"){
                            vid=parameters[i].split("=")[1];
                            break;
                        }
                    };
                    var attachmentElement = document.createElement("div");
                    attachmentContainer.appendChild(attachmentElement);
                    attachmentElement.setAttribute("title",match);
                    attachmentElement.setAttribute("class","attachmentElement youtubePreviewPicture");
                    attachmentElement.style.background="url('http://img.youtube.com/vi/"+vid+"/hqdefault.jpg') 50% 50% no-repeat;";
                    attachmentElement.appendChild(createRemoveAttachmentButton());
                    return '';
                }
                /*var attachmentElement = document.createElement("div");
                	attachmentElement.setAttribute("title",match);
                attachmentContainer.appendChild(attachmentElement);
                	attachmentElement.setAttribute("class","attachmentElement");
                	attachmentElement.appendChild(createRemoveAttachmentButton());
                    getSiteBasicInforation(attachmentElement);*/
                    return match;
                });
}
function submitAttachments(){
	var input = document.querySelector(".attachmentInput");
	var attachments = document.querySelector(".attachmentContainer").childNodes;
	for (var i = attachments.length - 1; i >= 0; i--) {
		input.value+=attachments[i].title+" ";
	};
	return true;
}
function getSiteBasicInforation(obj){
	var siteMetaHttp=new XMLHttpRequest();
  siteMetaHttp.onreadystatechange=function()
  {
   if (siteMetaHttp.readyState==4 && siteMetaHttp.status==200)
   { 
    metaObject = JSON.parse(siteMetaHttp.responseText.split("<!--")[0]);
    var element = document.createElement("img");
    element.setAttribute("src",metaObject.image);
    obj.appendChild(element);
    var element = document.createElement("p");
    element.appendChild(document.createTextNode(metaObject.title));
    obj.appendChild(element);
                	/*var element = document.createElement("p");
                	element.setAttribute("class","hint");
					element.appendChild(document.createTextNode(metaObject.description));
                 obj.appendChild(element);*/
             }
         }                    
         siteMetaHttp.open("GET","api/getSiteMetaData.php?url="+obj.title,true);
         siteMetaHttp.send();
     }
     document.querySelector(".fileInput").addEventListener("change",handleFileSelect,false);
     function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

      // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
    }

    var reader = new FileReader();

      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
          // Render thumbnail.
          var attachmentElement = document.createElement("div");
          document.querySelector(".attachmentContainer").appendChild(attachmentElement);
          attachmentElement.setAttribute("class","attachmentElement");
          attachmentElement.style.background=["url('",e.target.result,"') 50% 50% no-repeat"].join('');
          attachmentElement.appendChild(createRemoveAttachmentButton());
      };
  })(f);

      // Read in the image file as a data URL.
      reader.readAsDataURL(f);
  }
}
