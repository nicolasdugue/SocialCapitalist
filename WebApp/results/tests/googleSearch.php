<?php
$query = 'http://korben.info';
$url = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=".$query;

$body = file_get_contents($url);
$json = json_decode($body);

echo "Count Google Results : " . getNumberGoogleResults("http://youtube.com/Northernlion") . "<br/>";

echo "Count google results : " . count($json->responseData->results) . "<br/>";
echo "<pre>";
print_r($json->responseData->cursor);
echo "</pre>";



function getNumberGoogleResults($url){
  if($url != ""){
    $request = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=" . $url;
    $body = file_get_contents($request);
    $json = json_decode($body);
    
    return $json->responseData->cursor->resultCount;
    //Pour recuperer un entier au lieu d'un string : 
      //return $json->responseData->cursor->resultCount;

  }
  return -1;  
}
?>