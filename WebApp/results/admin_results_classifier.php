<?
/**
 *  Allow to get multiple classifier results from the database (used in the admin page to display the classifier table)
 */

/* Start session and load lib */
session_start();

// connexion to mongo database
include_once("../mongodb.php");

// number max of results to return
$n = (isset($_GET["n"])) ? $_GET["n"] : 20;

// offset of where to start
$from = (isset($_GET["from"])) ? $_GET["from"] : 0;

$res = $myDb->getCoefs();

header('Content-Type: text/plain');

echo json_encode(iterator_to_array($res));
