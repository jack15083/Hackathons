<?php 
require_once 'game.php';
require_once 'contestant.php';
require_once 'application.php';
require_once 'csvtoarray.php';
$csv = new csvtoarray("applicants.csv");
$data = $csv->get_array();
$app = new Application($data);
$app->run();

