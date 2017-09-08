<?php
include_once('nlp/autoloader.php');
use NlpTools\Tokenizers\WhitespaceTokenizer;
use NlpTools\Models\FeatureBasedNB;
use NlpTools\Documents\TrainingSet;
use NlpTools\Documents\TokensDocument;
use NlpTools\FeatureFactories\DataAsFeatures;
use NlpTools\Classifiers\MultinomialNBClassifier;
use \NlpTools\Similarity\CosineSimilarity;


$s1 = "I am happy";
$s2 = "Hello I am Tennessy";
$s3 = "Who the fuck is happy";
 
$tok = new WhitespaceTokenizer();
$cos = new CosineSimilarity();

$setA = $tok->tokenize($s1);
$setB = $tok->tokenize($s2);
$setC = $tok->tokenize($s3);

$AB = $cos->similarity($setA, $setB);
$AC = $cos->similarity($setA, $setC);
$BC = $cos->similarity($setC, $setB);

echo "<pre>";
print_r($setA);

echo "A et B : " . $AB;
echo "<br/>";

echo "A et C : " . $AC;
echo "<br/>";

echo "C et B : " . $BC;
echo "<br/>";

echo "Moyenne : " . ($AB + $AC + $BC)/3;
echo "</pre>";

$occ = array();
count_occ($setA, $occ);
count_occ($setB, $occ);
count_occ($setC, $occ);
$newSet = array();
foreach($occ as $m => $nb){
  for($i=0; $i<$nb-1; $i++){
    array_push($newSet, $m);
  }
}

$concatStr = $s1 ." ". $s2 ." ". $s3;
$concatSet = $tok->tokenize($concatStr);
$methodPerso = $cos->similarity($concatSet, $newSet);

echo "<pre>";
echo "occ : ";
print_r($occ);
echo "<br/>";

echo "NewSet : ";
print_r($newSet);
echo "<br/>";

echo "concatStr : ";
print_r($concatStr);
echo "<br/>";

echo "concatSet : ";
print_r($concatSet);
echo "<br/>";

echo "methodPerso : ";
print_r($methodPerso);
echo "<br/>";


echo "</pre>";

function count_occ($list, &$occ){
  foreach($list as $m){
    if(array_key_exists($m, $occ)){
      $occ[$m]++;
    }
    else{
      $occ[$m] = 1;
    }
  }
}

function getSimilarityFromTimeline($timeline){
  $tok = new WhitespaceAndPunctuationTokenizer();
  $cos = new CosineSimilarity();
  $similarityArray = array();
  for($i=0; $i<count($timeline); $i++){
    $setA = $tok->tokenize($timeline[$i]->{'text'});
    for($j=$i+1; $j<count($timeline); $j++){
      $setB = $tok->tokenize($timeline[$j]->{'text'});
      array_push($cos->similarity($setA, $setB), $similarityArray);
    }
  }
  return $similarityArray;
}

?>