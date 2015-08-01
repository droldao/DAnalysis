<?php
header('Content-Type: text/html; charset=utf-8');

$arquivo = file_get_contents('database\AllCards-x.json');
$database = json_decode($arquivo);

$creature_type_list = [];

foreach (get_object_vars($database) as $card) {
  if (isset($card->types) && in_array('Creature', $card->types) && isset($card->legalities->Modern) && $card->legalities->Modern === 'Legal') {
   $creature_type_list = array_merge($creature_type_list, $card->subtypes);
  }
}
$creature_type_list = array_unique($creature_type_list);
sort($creature_type_list);


$database = json_encode($creature_type_list);

$fp = fopen('database\creature_types.json', 'w');
fwrite($fp, $database);
fclose($fp);

die('OK');

