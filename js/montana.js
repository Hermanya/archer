function ajax(){
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function()
    {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
      {
        document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
      }
    }
  xmlhttp.open("GET","",true);
  xmlhttp.send();
}


function byId(id){
  return document.getElementById(id);
}
function byClass(class){
  return document.getElementsByClassName(class);
}