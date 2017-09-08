<?
/* Start session and load lib */
session_start();

// connexion to mongo database
include_once("../mongodb.php");

$id = (isset($_GET["id"])) ? $_GET["id"] : null;
$val = (isset($_GET["val"])) ? $_GET["val"] : 0;

$myDb->setCoefActive($id, $val);

header('Content-Type: text/plain');

echo 'ok';
