<?php 
function file_get_contents_curl($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}
$url = $_GET["url"];
if(empty($url) || is_string($url))
$html = file_get_contents_curl($url);

//parsing begins here:
$doc = new DOMDocument();
@$doc->loadHTML($html);
$nodes = $doc->getElementsByTagName('title');

//get and display what you need:
$title = $nodes->item(0)->nodeValue;

$metas = $doc->getElementsByTagName('meta');

for ($i = 0; $i < $metas->length; $i++)
{
    $meta = $metas->item($i);
    if($meta->getAttribute('property') == 'og:description')
        $description = $meta->getAttribute('content');
    if($meta->getAttribute('property') == 'og:image')
        $image = $meta->getAttribute('content');
    if($meta->getAttribute('property') == 'og:title')
        $title = $meta->getAttribute('content');
}

echo "{\"title\": \"".$title."\",".
      "\"description\": \"".$description."\",".
        "\"image\": \"".$image."\"}";
?>