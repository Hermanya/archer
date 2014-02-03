function replaceMarkup(text){
    text = text.replace(/(&gt;){2}([0-9]+)/g,"<a class=\"answer\" href=\"#$2\">>>$2</a>");
    text = text.replace(/&gt;(.*)<br>/g,"<span class=\"quot\">>$1<br></span>");
    text = text.replace(/\*{2}(.*?)\*{2}/g,"<strong>$1</strong>");
    text = text.replace(/\[b\]/g,"<strong>");
    text = text.replace(/\[\/b\]/g,"</strong>");
    text = text.replace(/\*(.*?)\*/g,"<em>$1</em>");
    text = text.replace(/\[i\]/g,"<em>");
    text = text.replace(/\[\/i\]/g,"</em>");
    text = text.replace(/%{2}(.*?)%{2}/g,"<span class=\"hiddenText\">$1</span>");
    text = text.replace(/\[s\]/g,"<span class=\"hiddenText\">");
    text = text.replace(/\[\/s\]/g,"</span>");
    text = text.replace(/\-{2}2(.*?)\-{2}/g,"<span class=\"linedText\">$1</span>");
    text = text.replace(/\[l\]/g,"<span class=\"linedText\">");
    text = text.replace(/\[\/l\]/g,"</span>");
    text = text.replace(/(?:https?:\/\/\S+)|(?:www.\S+)/g,function(match){
                match = match.split("<br>")[0];
                    if(match.indexOf('http://') !== 0 && match.indexOf('https://') !== 0)
                {
                    match = 'http://' + match;
                }
                var parser = document.createElement('a');
                parser.href =   match;

                // images
                if(parser.pathname.match(/\.(png|jpg|PNG|JPG|gif|GIF)$/))
                {   
                    return '<div  class="threadImage" style="background-image:url(\''+match+'\');"  title="'+ parser.hostname + '"></div>';
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
                    //return "";//'<img class="postImage youtubePreviewPicture" src="http://img.youtube.com/vi/'+vid+'/hqdefault.jpg" title="'+vid+'">';
                    //return '<iframe class="embedded-video" src="http://www.youtube.com/embed/'+vid+'" width="640" height="360" allowfullscreen></iframe>';
                }
                //links
                return '<a href="'+match+'" target="_blank">'+parser.hostname+'</a>';
    });
    text = text.replace(/^#(([^(\.,!\?\+\)\("'\&\$\^#\n\s\r\f<>)])+)/g,"<a class=\"tag\" href=\"search.php?offset=0&tag=$1\">#$1</a>");
    text = text.replace(/\s#(([^(\.,!\?\+\)\("'\&\$\^#\n\s\r\f<>)])+)/g,"&nbsp;<a class=\"tag\" href=\"search.php?offset=0&tag=$1\">#$1</a>");
    return text;
}
