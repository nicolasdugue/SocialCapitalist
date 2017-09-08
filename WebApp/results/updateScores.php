<?php
/**
 *
 * Mise à jour des score de tout les utilisateurs de la database
 *
 * Cela evite de devoir appeler results.php (et de faire les requetes Twitter) à chaque mise à jour du classifieur ...
 *
 */

include_once('../mongodb.php');
include_once("include.php");

$res = $myDb->getAllUsers();

$values = $myDb->getActiveCoefs()->getNext();

foreach ($res as $val) {

    $db_vals = $val['vals'];
    $username = $val['username'];

    $statuses = $db_vals['statuses'];
    $listed = $db_vals['listed'];
    $favorites = $db_vals['favorites'];
    $friends = $db_vals['friends'];
    $followers = $db_vals['followers'];
    $avg_length = $db_vals['avg_length'];
    $avg_hashtags = $db_vals['avg_hashtags'];
    $avg_url = $db_vals['avg_url'];
    $avg_mentions = $db_vals['avg_mentions'];
    $avg_retweets = $db_vals['avg_retweets'];
    $percent_retweets = $db_vals['percent_retweets'] * 100;
    $avg_retweeted = $db_vals['avg_retweeted'];
    $s2 = $db_vals['follow'];
    $s1 = $db_vals['management'];
    $s0 = $db_vals['web'];
    $s3 = $db_vals['automatic'];
    $s4 = $db_vals['tierces'];
    $s5 = $db_vals['devices'];

    $feat = array($statuses, $listed, $favorites, $friends, $followers, $avg_length, $avg_hashtags, $avg_url, $avg_mentions, $avg_retweets, $percent_retweets, $avg_retweeted, $s2, $s1, $s0, $s3, $s4, $s5);

    $score = getScore($values, $feat);

    $myDb->updateScore($username, $score);

    echo $username . ' ' . $score . ' ' . $db_vals['score'] . '<br />';

}

echo 'done';
