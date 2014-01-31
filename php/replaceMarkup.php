<?php
function replaceMarkup($string) {

	$string = preg_replace('#&#', "&amp;", $string);
	$string = preg_replace('#<#', "&lt;", $string);
	$string = preg_replace('#"#', "&quot;", $string);
	$string = preg_replace('#\'#', "&#39;", $string);
	$string = preg_replace('#(^|\s)>{2}([0-9]*)#', "<a class=\"answer\" href=\"#$2\">&gt;&gt;$2</a>", $string);
	$string = preg_replace('#(^|\s)>{1}(.*)#', "<span class=\"quot\">&gt;$2</span>", $string);
	$string = nl2br($string,false);
	$string = preg_replace('#</span><br>#', "</span>", $string);
		//$string = preg_replace('#^(.*)\>(.*)#m', "$1<span class=\"quot\">&gt;$2</span> ", $string);
	$string = preg_replace('#\*{2}(.*?)\*{2}#', "<b>$1</b>", $string);
	$string = preg_replace('#\[b\](.*?)\[/b\]#', "<b>$1</b>", $string);
	$string = preg_replace('#\*{1}(.*?)\*{1}#', "<em>$1</em>", $string);
	$string = preg_replace('#\[i\](.*?)\[/i\]#', "<em>$1</em>", $string);
	$string = preg_replace('#%{2}(.*?)%{2}#', "<span class=\"hiddenText\">$1</span> ", $string);
	$string = preg_replace('#\[s\](.*?)\[/s\]#', "<span class=\"hiddenText\">$1</span> ", $string);
	$string = preg_replace('#\-{2}(.*?)\-{2}#', "<span class=\"linedText\">$1</span> ", $string);
	$string = preg_replace('#\[l\](.*?)\[/l\]#', "<span class=\"linedText\">$1</span> ", $string);
	//	$string = preg_replace_callback('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/', function($arr)
		$string = preg_replace_callback('#(?:https?://\S+)|(?:www.\S+)#', function($arr)
{
    if(strpos($arr[0], 'http://') !== 0 && strpos($arr[0], 'https://') !== 0)
    {
        $arr[0] = 'http://' . $arr[0];
    }
    $url = parse_url($arr[0]);

    // images
    if(preg_match('#\.(png|jpg|gif)$#', $url['path']))
    {
        return '<a href="'. $arr[0] . '"><img class="postImage" src="'. $arr[0] . '" /></a>';
    }
    // youtube
    if(in_array($url['host'], array('www.youtube.com', 'youtube.com'))
      && $url['path'] == '/watch'
      && isset($url['query']))
    {
        parse_str($url['query'], $query);
        return sprintf('<iframe class="embedded-video" src="http://www.youtube.com/embed/%s" width="640" height="360" allowfullscreen></iframe>', $query['v']);
    }
    //links
    return sprintf('<a href="%1$s">%1$s</a>', $arr[0]);
}, $string);
	return $string;
}
?>