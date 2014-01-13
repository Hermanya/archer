var posts = document.getElementsByClassName("post");
var answers = document.getElementsByClassName("answer");
var postId,answerId, numberOfAnswers,numberOfAnswersElement;
for (var i = answers.length - 1; i >= 0; i--) {
	postId = parseInt(answers[i].href.split("#")[1]);
	answerId = answers[i].parentNode.parentNode.id;

	for (var j = posts.length - 1; j >= 0; j--) {
		if(posts[j].id==postId){
			var answerLink = document.createElement("a");
			answerLink.setAttribute("href","#"+answerId);
			answerLink.setAttribute("class","postAnswerLink");
			answerLink.appendChild(document.createTextNode("<<"+answerId));
			posts[j].getElementsByClassName("postAnswers")[0].appendChild(answerLink);
		}
	};
};
for (var i = posts.length - 1; i >= 0; i--) {
	numberOfAnswers = posts[i].getElementsByClassName("postAnswers")[0].childNodes.length;
	if (numberOfAnswers>0) {
		numberOfAnswersElement = posts[i].getElementsByClassName("postAnswersNumber")[0];
		numberOfAnswersElement.appendChild(
												document.createTextNode(numberOfAnswers+" ")
										   );
		var answerSymbol = document.createElement("span");
		answerSymbol.setAttribute("class","glyphicon glyphicon-comment");
		numberOfAnswersElement.appendChild(answerSymbol);
		numberOfAnswersElement.addEventListener("click",function(){
						var div = this.parentNode.getElementsByClassName("postAnswers")[0];
						if(div.style.display=="block"){
							div.style.display="none";
						}else{
							div.style.display="block";
						}
				},false);
	};
};
function getSelectionHtml() {
    var html = "";
    if (typeof window.getSelection != "undefined") {
        var sel = window.getSelection();
        if (sel.rangeCount) {
            var container = document.createElement("div");
            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                container.appendChild(sel.getRangeAt(i).cloneContents());
            }
            html = container.innerHTML;
        }
    } else if (typeof document.selection != "undefined") {
        if (document.selection.type == "Text") {
            html = document.selection.createRange().htmlText;
        }
    }
    return html;
}
function answer(e){
	e.preventDefault();
	if (this!=e.target)
		return;
	if(e.target.tagName=="P"){
		if (! e.ctrlKey)
			return;
	}
	var parent = this;
	while(parent.id==""){
		parent = parent.parentNode;
	}
	var idString = parent.id;
	var myText = getSelectionHtml();
	myText = myText.replace(/<.*>/g,"");
	var container = document.querySelector(".metaContainer");
	var form = document.querySelector("#answerForm");
	container.insertBefore(
		form,
		document.getElementById(idString).nextSibling
		);
	var pageYOffset = window.pageYOffset + window.innerHeight - form.offsetTop - form.offsetHeight;
	window.scrollBy(0,-pageYOffset);
	insertAtCursor((document.querySelector("textarea").value.length==0?"":"\n")+
					">>"+idString+(myText==""?"\n ":("\n> "+myText+"\n ")),"");
}
