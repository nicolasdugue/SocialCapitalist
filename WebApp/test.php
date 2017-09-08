<?php
/**
 * @file
 * 
 */

/* Load required lib files. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ./clearsessions.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/* Get logged in user to help with tests. */
$user = $connection->get('account/verify_credentials');

$active = true;
$id=$_POST['screen_name'];

$method = "followers/ids";
$parameters=array("screen_name" => $id, cursor => "-1");
$response = $connection->get($method, $parameters);

$followers = $response->{'ids'};
$parameters=array("screen_name" => $id, cursor => "-1");
$method = "friends/ids";
$response2=$connection->get($method, $parameters);

$followees=$response2->{'ids'};

$intersection=array_intersect($followers, $followees);

$requete="users/show";
$parameter=array("screen_name" => $id);
$res=$connection->get($requete, $parameters);
echo $res->{'name'}. " : @".$res->{'screen_name'}."<br />";

echo count($followers). " followers<br />";
echo count($followees). " followees<br />";
echo count($intersection) . "bi<br />";
echo count($intersection)/min(count($followers), count($followees)). " overlap<br />";

$requete = "application/rate_limit_status";
$parameter=array("resources" => "friends");
$res=$connection->get($requete, $parameters);
echo "Requetes de followees restantes : " . $res->{"resources"}->{"friends"}->{"/friends/ids"}->{"remaining"}."<br />";
echo "Requetes de followers restantes : " . $res->{"resources"}->{"followers"}->{"/followers/ids"}->{"remaining"}."<br />";

//foreach ($followers as $value)
	//echo $value . "<br /> ";
//foreach ($followees as $value)
//	echo $value . "<br />"; 

//print_r($_SESSION);
//echo "Token : " . $_SESSION['acces_token']['oauth_token']."<br />";
//echo "Token secret : ". $_SESSION['oauth_token_secret']."<br />";
