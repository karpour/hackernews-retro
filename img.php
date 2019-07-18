<?php
require __DIR__ . '/vendor/autoload.php';

use JBZoo\Image\Image;
use JBZoo\Image\Filter;
use JBZoo\Image\Exception;

$url = $_GET['url'];

$type = (isset($_GET['type']))?$options['type']:'gif';

// @todo type check
// @todo handle base64 strings
// @todo handle svg

header("Content-Type: image/".$type); 

try { 
    $img = (new Image(file_get_contents($url)))->bestFit(600, 200);
    imagegif($img->getImage());
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}