<?php
header('Content-Type: text/html; charset=utf-8');

define("EM_DASH", "[");

$ability_list = [];

$ability_list[] = new KeywordAbility('flying');
$ability_list[] = new KeywordAbility('vigilance');
$ability_list[] = new KeywordAbility('flash');
$ability_list[] = new KeywordAbility('first strike');
$ability_list[] = new KeywordAbility('trample');
$ability_list[] = new KeywordAbility('delve');
$ability_list[] = new KeywordAbility('deathtouch');
$ability_list[] = new KeywordAbility('lifelink');
$ability_list[] = new KeywordAbility('haste');
$ability_list[] = new KeywordAbility('prowess');
$ability_list[] = new KeywordAbility('indestructible');
$ability_list[] = new KeywordAbility('battle cry');
$ability_list[] = new KeywordAbility('annihilator', KeywordAbility::KEYWORD_WITH_VALUE);
$ability_list[] = new KeywordAbility('modular', KeywordAbility::KEYWORD_WITH_VALUE);
$ability_list[] = new KeywordAbility('protection', KeywordAbility::KEYWORD_WITH_OBJECT, 'from');
$ability_list[] = new KeywordAbility('affinity',   KeywordAbility::KEYWORD_WITH_OBJECT, 'for');

$ability_list[] = new KeywordAbility('metalcraft', KeywordAbility::KEYWORD_DELETE);
$ability_list[] = new KeywordAbility('threshold',  KeywordAbility::KEYWORD_DELETE);

$database = json_encode($ability_list);

$fp = fopen('database\keyword_abilities.json', 'w');
fwrite($fp, $database);
fclose($fp);

class KeywordAbility {

  CONST KEYWORD_WITH_COST = 'cost';
  CONST KEYWORD_WITH_VALUE = 'value';
  CONST KEYWORD_WITH_OBJECT = 'object';
  CONST KEYWORD_DELETE = 'delete';

  public $name;
  public $type;
  public $multi;

  public function __construct($name, $type = null, $key = '', $multi = false) {
    $this->name = $name;
    $this->type = $type;
    $this->key  = $key;
    $this->multi = $multi;
  }
}

die('OK');
