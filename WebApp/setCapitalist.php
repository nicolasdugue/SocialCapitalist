<?php

include_once('mongodb.php');

$username = (isset($_GET["username"])) ? $_GET["username"] : NULL;
$id = (isset($_GET["id"])) ? $_GET["id"] : NULL;
$value = (isset($_GET["value"])) ? $_GET["value"] : NULL;

if ($username != NULL && $value != NULL ) {

    $myDb->setSocialCapitalist($username, $value);

} else if ($id != NULL && $value != NULL) {

    $myDb->setSocialCapitalistById($id, $value);

}