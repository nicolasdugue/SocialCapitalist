<?php

include_once('mongodb.php');
include_once('results/scripts/keys.php');
include_once('results/scripts/scores.php');

$type = (isset($_GET["type"])) ? $_GET["type"] : NULL;
$username = (isset($_GET["username"])) ? $_GET["username"] : NULL;

if ($type == "kred") {

    $kred_score = ceil(getKredScore($username, $key_kred_app, $key_kred_id));

    $myDb->setKredScore($username, $kred_score);

    $a = array(
        'score' => $kred_score
    );

    echo json_encode($a);

} elseif ($type == "klout") {

    $klout_score = ceil(getKloutScore(getIdKlout($username, $key_klout_id), $key_klout_score));

    $myDb->setKloutScore($username, $klout_score);

    $a = array(
        'score' => $klout_score
    );

    echo json_encode($a);

} elseif ($type == "both") {

    $kred_score = ceil(getKredScore($username, $key_kred_app, $key_kred_id));
    $klout_score = ceil(getKloutScore(getIdKlout($username, $key_klout_id), $key_klout_score));

    $myDb->setKloutScore($username, $klout_score);
    $myDb->setKredScore($username, $kred_score);

    $a = array(
        'klout_score' => $klout_score,
        'kred_score' => $kred_score
    );

    echo json_encode($a);
}