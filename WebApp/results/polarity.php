<?php
include_once('nlp/autoloader.php');
use NlpTools\Tokenizers\WhitespaceTokenizer;
use NlpTools\Models\FeatureBasedNB;
use NlpTools\Documents\TrainingSet;
use NlpTools\Documents\TokensDocument;
use NlpTools\FeatureFactories\DataAsFeatures;
use NlpTools\Classifiers\MultinomialNBClassifier;
use NlpTools\Similarity\CosineSimilarity;
use NlpTools\Tokenizers\WhitespaceAndPunctuationTokenizer;


//Returns an array ("class" -> pos/neg , "pos" -> score class pos , "neg" -> score class neg)
function getPolarityTweet($tweet){
	$PATH_SERIALIZED_MODEL = "models/dataset500000";
	//TODO : Appel fonctions nettoyage
	$model = unserialize(file_get_contents($PATH_SERIALIZED_MODEL));
	$tok = new WhitespaceTokenizer();
	$ff = new DataAsFeatures();
	$cls = new MultinomialNBClassifier($ff,$model);

	$doc = new TokensDocument($tok->tokenize($tweet));

	$prediction = $cls->classify(array('neg','pos'), $doc);

	return array(
		"class" => $prediction,
		"pos" => $cls->getScore("pos", $doc),
		"neg" => $cls->getScore("neg", $doc)
		);
}

function getPolarityFromTimeline($tweets){
	$PATH_SERIALIZED_MODEL = "models/dataset500000";
	//TODO : Appel fonctions nettoyage
	$model = unserialize(file_get_contents($PATH_SERIALIZED_MODEL));
	$tok = new WhitespaceTokenizer();
	$ff = new DataAsFeatures();
	$cls = new MultinomialNBClassifier($ff,$model);

	
	$array_polarity = array("pos" => 0, "neg" => 0);
	foreach($tweets as $t){
		$doc = new TokensDocument($tok->tokenize($t->{"text"}));
		$prediction = $cls->classify(array('neg','pos'), $doc);	
		//array_push($array_polarity, $prediction);
		$array_polarity[$prediction] += 1;
	}
	return $array_polarity;
}

function getSimilarityFromTimeline($timeline){
  $tok = new WhitespaceAndPunctuationTokenizer();
  $cos = new CosineSimilarity();
  $similarityArray = array();
  for($i=0; $i<count($timeline)-5; $i++){
    $setA = $tok->tokenize($timeline[$i]->{'text'});
    for($j=$i+1; $j<count($timeline); $j+=5){
      $setB = $tok->tokenize($timeline[$j]->{'text'});
      array_push($similarityArray, $cos->similarity($setA, $setB));
    }
  }
  
  $mean = count($similarityArray) > 0 ? array_sum($similarityArray) / count($similarityArray) : 0;
  $max = count($similarityArray) > 0 ? max($similarityArray) : 0;
  $std_dev = count($similarityArray) > 0 ? stats_standard_deviation($similarityArray) : 0;
  
  $res = array(
  	"mean" => $mean,
  	"max" => $max,
  	"std_deviation" => $std_dev
  );
	
  return $res;
}

?>