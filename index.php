<?php
// main class
require_once 'controller/class.MyApplication.php';

$app = new MyApplication();
// Inicialization 
$app->init();
// run appp
$app->run();
// end
?>
