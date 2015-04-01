<?php
// Get the necessary libraries
require_once __DIR__.'/vendor/autoload.php'; 

// Symfony Console Application 
use Symfony\Component\Console\Application; 

// Finder Command
use Recipe_Finder\FinderCommand;

$app = new Application();
$app->add(new FinderCommand());
$app->run();
?>