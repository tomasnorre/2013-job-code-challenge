<?php
/**
 * This is the index file for code-project, this initiates 
 * the controllers etc need to produce the XML output file.
 */

// Include controller and view
require_once 'controller/MusicReleaseController.php';
require_once 'view/View.php';

$musicRelease = new MusicRelease();

// Initiate Control and View
$controller = new MusicReleaseController($musicRelease); 
$releases = $controller->init();

// Initiate resultsa
$releaseArray = array();

// Loops through releases and adds them to releaseArray
foreach ($releases as $release) {
    $view = new View($release);
    $releaseArray[] = $view->xmlOutputToFile();
}

// Create the XML-File, see README.txt for short description on View.php-class 
$view->createXMLFile($releaseArray);
