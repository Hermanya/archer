	var rehttp=new XMLHttpRequest(), numberOfNewPosts=0;
  /**
    Display posts, update the title
  */
  rehttp.onreadystatechange=function()
  {
    if (rehttp.readyState==4 && rehttp.status==200)
    { 
      postsArray = JSON.parse(rehttp.responseText.split("<!--")[0]);
      if(postsArray.length==0){
        if (!document.querySelector(".post")) {
          document.querySelector(".metaContainer").style.textAlign="center";
          var element = document.createElement("img");
          element.setAttribute("class","fullSizeImage");
          element.setAttribute("src","images/animals.png");
          document.querySelector(".metaContainer").appendChild(element);
          var element = document.createElement("h1");
          element.appendChild(document.createTextNode("404"));
          document.querySelector(".metaContainer").appendChild(element);
          var element = document.createElement("h5");
          element.appendChild(document.createTextNode("thread is not found"));
          document.querySelector(".metaContainer").appendChild(element);
          document.querySelector(".formOutterContainer").parentNode.
          removeChild(document.querySelector(".formOutterContainer"));
          document.querySelector(".nextPage").parentNode.
          removeChild(document.querySelector(".nextPage"));
          document.querySelector("body").removeChild(document.querySelector("footer"));
        }else{
          document.getElementById("update").innerHTML = "nothing new";
          window.setTimeout(function(){
            document.getElementById("update").innerHTML = '<span class="glyphicon glyphicon-refresh"> </span> update';
          },2000);
        }
      }else{
        document.getElementById("update").innerHTML = '<span class="glyphicon glyphicon-refresh"> </span> update';
      }
      for (var i = 0; i < postsArray.length; i++) {
       createPostElement(postsArray[i]);
     };
     var numberOfPosts = document.querySelector("title").innerHTML.split(" ")[0],
     splited =  numberOfPosts.split("+");
     if (splited.length==1){
       numberOfPosts = parseInt(splited[0]);
       document.querySelector("title").innerHTML=numberOfPosts+"+"+postsArray.length+((numberOfPosts+postsArray.length-1)==1?" post itt":" posts itt");
     }else{
       numberOfPosts = parseInt(splited[0])+parseInt(splited[1]);
       document.querySelector("title").innerHTML=splited[0]+"+"+(parseInt(splited[1])+postsArray.length)+((numberOfPosts+postsArray.length-1)==1?" post itt":" posts itt");
     }
     if ( 'ontouchstart' in window || 'onmsgesture'in window){
      document.addEventListener("touchstart",presense,false);
    }else{	
      document.addEventListener("mousemove",presense,false);
    }
    var ifPosted = window.location.href.split("#");
    if (ifPosted.length==2){
     if (ifPosted[1]=="posted"){
      document.getElementById("answerForm").scrollIntoView();
    }
  }
}
    }                     
    var threadId = document.getElementsByClassName("metaContainer")[0].id; 
    /**
      Request posts from the server
    */
    function loadNewPosts(){
      document.getElementById("update").innerHTML = "loading...";
      var posts = document.getElementsByClassName("post"); 
      for (var i = posts.length - 1; i >= 0; i--) {
        posts[i].querySelector(".timestamp").removeChild(posts[i].querySelector(".timestamp").firstChild);
        posts[i].querySelector(".timestamp").appendChild(document.createTextNode(humanTiming(posts[i].querySelector(".timestamp").getAttribute("ts")) ) );
      };
      var lastPostId = posts[posts.length-1].id;
      rehttp.open("GET","api/getPosts.php?thread_id="+threadId+"&last_post_id="+lastPostId,true);
      rehttp.send();

    }

    rehttp.open("GET","api/getPosts.php?thread_id="+threadId,true);
    rehttp.send();

    var updateTimeout, interval=5000;
    /**
      This is pretty straight-forward
    */
    function updateFunction(){
     loadNewPosts();
     interval+=5000;
     updateTimeout = setTimeout(updateFunction,interval);
   }
   /**
      Set a recursive timeout
   */
   function autoUpdate(){
     if (this.checked){
      updateTimeout = setTimeout(updateFunction,interval);
    }else{
      clearTimeout(updateTimeout);
    }
  }
  document.getElementById("update").addEventListener("click",loadNewPosts,false);
  document.getElementById("auto").addEventListener("click",autoUpdate,false);
  /**
      Update the title if user is present 
  */
  function presense(){
   if ( 'ontouchstart' in window || 'onmsgesture'in window){
     document.removeEventListener("touchstart",presense,false);
   }else{	
     document.removeEventListener("mousemove",presense,false);
   }
   var numberOfPosts = document.querySelector("title").innerHTML.split(" ")[0],
   splited =  numberOfPosts.split("+");
   numberOfPosts =  parseInt(splited[0])+parseInt(splited[1]);
   document.querySelector("title").innerHTML=numberOfPosts+((numberOfPosts-1)==1?" post itt":" posts itt");
 }
