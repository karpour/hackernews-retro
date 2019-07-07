<?php
require_once __DIR__ . '/vendor/autoload.php';
require './HackerNews.php';

$stories = HackerNews::getStories(HackerNews::getStoryIds("top"),10,10);

foreach ($stories as $idx => $story) {
    echo $idx . ". " . $story->title . "\n";
}