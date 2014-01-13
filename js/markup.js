function insertAtCursor(value1,value2) {
	myField = document.querySelector("textarea");
    //IE support
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = value1+value2;
    }
    //MOZILLA and others
    else {//if (myField.selectionStart) {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos)
            + value1
            + myField.value.substring(startPos,endPos)
            + value2
            + myField.value.substring(endPos, myField.value.length);
            myField.setSelectionRange(startPos+value1.length,startPos+value1.length);
   /* } else {
        myField.value += value1+value2;
        myField.setSelectionRange(value1.length,value1.length);*/
    }
    myField.scrollTop = myField.scrollHeight;
    myField.focus();

}
document.getElementById("bold").addEventListener("click",function(){insertAtCursor("[b]","[/b]");});
document.getElementById("italics").addEventListener("click",function(){insertAtCursor("[i]","[/i]");});
document.getElementById("hidden").addEventListener("click",function(){insertAtCursor("[s]","[/s]");});
document.getElementById("lined").addEventListener("click",function(){insertAtCursor("[l]","[/l]");});
document.querySelector("textarea").addEventListener("keypress", function(e){
        if (e.ctrlKey){
            if (e.keyCode==10)
                document.querySelector(".postingForm").submit();
            if (navigator.userAgent.toLowerCase().indexOf('chrome') > -1){
            if (e.keyCode==2)
                insertAtCursor("[b]","[/b]"); 
            if (e.keyCode==9)
                insertAtCursor("[i]","[/i]");
            if (e.keyCode==29)
                insertAtCursor("[s]","[/s]"); 
            if (e.keyCode==27)
                insertAtCursor("[s]","[/s]"); 
            if (e.keyCode==13)
                insertAtCursor("[l]","[/l]");
            if (e.keyCode==10||e.keyCode==2||e.keyCode==9||e.keyCode==29||e.keyCode==27||e.keyCode==13)
                e.preventDefault();
        }
        }
    },false);