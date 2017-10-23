<?php
$file = fopen('json/dataSelect.json', "r");
$getJSON = stream_get_contents($file);
$json = json_decode($getJSON);
foreach ($json as $item) {
echo $item;
}
?>
