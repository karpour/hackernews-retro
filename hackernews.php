<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://hacker-news.firebaseio.com/v0/topstories.json');
$result = curl_exec($ch);
//curl_close($ch);

$obj = json_decode($result);

$idx = 10;

for($i=$idx;$i<$idx+10 && $i<count($obj);$i++) {
    echo $i.". ".$obj[$i]."\n";
    curl_setopt($ch, CURLOPT_URL, 'https://hacker-news.firebaseio.com/v0/item/'.$obj[$i].'.json');
    $result = curl_exec($ch);
    echo var_dump(json_decode($result));
}


curl_close($ch);