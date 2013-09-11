<?php
session_start();
error_reporting(E_ALL);

// Requires
require_once('/config.php');
require_once('/sys/registry.php');
require_once('/sys/system.php');
require_once('/sys/controller.php');
require_once('/sys/model.php');

// Instances
$model		= new Model();
$system		= new System();
$system->run();
?>