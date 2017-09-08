<?php
/**
 * Execute the python script of the classifier
 */
ini_set('max_execution_time', -1);
error_reporting(E_ALL);

//$command = escapeshellcmd('python ../../logregtest4.py');
try {

    $command = escapeshellcmd('python ../../logregtest6.py ../../datafinal/neg ../../datafinal/pos');
    $output = shell_exec($command);
    echo $output;

} catch (Exception $e) {

    var_dump($e);

}
