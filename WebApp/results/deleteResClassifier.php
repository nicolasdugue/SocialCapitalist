<?
/* Start session and load lib */
session_start();

// connexion to mongo database
include_once("../mongodb.php");

$id = (isset($_GET["id"])) ? $_GET["id"] : null;

$myDb->deleteResClassifier($id);

header('Content-Type: text/plain');

echo 'ok';
