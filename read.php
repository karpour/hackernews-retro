<?php
require __DIR__ . '/vendor/autoload.php';
use andreskrey\Readability\Configuration;
use andreskrey\Readability\ParseException;
use andreskrey\Readability\Readability;

require __DIR__ . '/get-fav.php';

header('Content-Type: text/html; charset=utf-8');

$prefixURL = "read.php?url=";
$prefixIMGURL = "img.php?url=";

$url = $_GET['url'];
$configuration = new Configuration();
$configuration
    ->setFixRelativeURLs(true)
    ->setOriginalURL($url);

$readability = new Readability($configuration);

$html = file_get_contents($url);

$content = "Page failed to load";
$title = "Readability";
try {
    $readability->parse($html);
    // @todo Change img and href links in DOM
    $dom = $readability->getDOMDocument();
    foreach (iterator_to_array($dom->getElementsByTagName('a')) as $link) {
        $href = $link->getAttribute('href');
        if ($href) {
            $link->setAttribute('href', $prefixURL . $href);
        }
    }
    foreach (iterator_to_array($dom->getElementsByTagName('img')) as $image) {

        $src = $image->getAttribute('src');
        if ($src) {
            $image->setAttribute('src', $prefixIMGURL . $src);
        }
    }
    $headline = $readability->getTitle();
    $content = $dom->C14N();
    $title = $headline;

    $titleElement = $dom->getElementsByTagName("title");
    if ($titleElement->length > 0) {
        $title = $titleElement->item(0)->textContent;
        if (strpos($title, ' - ') !== false) {
            $title_split = split(' - ', $title);
            $title = $title_split(sizeof($title_split) - 1);
        }
    }
} catch (ParseException $e) {
    $content = sprintf('Error processing text: %s', $e->getMessage());
}

$grap_favicon = array(
    'URL' => $url, // URL of the Page we like to get the Favicon from
    'SAVE' => false, // Save Favicon copy local (true) or return only favicon url (false)
);
$favicon = grap_favicon($grap_favicon);

?>

<html>
<head>
    <title><?php echo $title; ?></title>
</head>
<body>
<font face="Arial">
    <h2><img src="<?php echo $favicon; ?>">Sitename</h2>
    <h1><?php echo $headline; ?></h1>
    <hr>
    <?php echo $content ?>
</font>
</body>