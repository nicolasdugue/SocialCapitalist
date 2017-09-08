<?
/**
 *  Allow to get multiple users from the database (used in the admin page to display the table)
 */

/* Start session and load lib */
session_start();

// connexion to mongo database
include_once("../mongodb.php");

// number max of results to return
$n = (isset($_GET["n"])) ? $_GET["n"] : 20;

// offset of where to start
$from = (isset($_GET["from"])) ? $_GET["from"] : 0;

// array of criteria. It should have 'search' with a string, 'sort_by' with a column name and 'sort_inv' with the order of the sort (-1 or 1)
$crit = (isset($_GET["s"])) ? $_GET["s"] : "";

$crits = json_decode($crit, true);

$res = $myDb->getUsers($n, $from, $crits['search'], $crits['sort_by'], $crits['sort_inv']);

header('Content-Type: text/plain');

echo json_encode($res);
