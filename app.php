<!DOCTYPE html>
<html>
    <head>
        <title>Hacker news</title>
        <meta charset="UTF-8">
    </head>
<body>
    <pre>
<?php
//require_once __DIR__ . '/vendor/autoload.php';
require './HackerNews.php';
require './UTFtoASCII.php';

//header('Content-Type: text/html; charset=utf-8');

$stories = HackerNews::getStories(HackerNews::getStoryIds("top"), 0, 10);

foreach ($stories as $idx => $story) {

    echo $idx+1 . ". " . $story->title . "\n";
}
?>
    </pre>
</body>
</html>