<?php
header('Content-Type: text/html; charset=utf-8');

$ability_list = [];

$ability_list[] = new KeywordAbility('flying',         KeywordAbility::STATIC_ABILITY);
$ability_list[] = new KeywordAbility('vigilance',      KeywordAbility::STATIC_ABILITY);
$ability_list[] = new KeywordAbility('flash',          KeywordAbility::STATIC_ABILITY);
$ability_list[] = new KeywordAbility('first strike',   KeywordAbility::STATIC_ABILITY);
$ability_list[] = new KeywordAbility('trample',        KeywordAbility::STATIC_ABILITY);
$ability_list[] = new KeywordAbility('delve',          KeywordAbility::STATIC_ABILITY);
$ability_list[] = new KeywordAbility('deathtouch',     KeywordAbility::STATIC_ABILITY);
$ability_list[] = new KeywordAbility('lifelink',       KeywordAbility::STATIC_ABILITY);
$ability_list[] = new KeywordAbility('haste',          KeywordAbility::STATIC_ABILITY);
$ability_list[] = new KeywordAbility('prowess',        KeywordAbility::TRIGGER_ABILITY);
$ability_list[] = new KeywordAbility('indestructible', KeywordAbility::STATIC_ABILITY);
$ability_list[] = new KeywordAbility('battle cry',     KeywordAbility::TRIGGER_ABILITY);
$ability_list[] = new KeywordAbility('annihilator',    KeywordAbility::TRIGGER_ABILITY, true);
$ability_list[] = new KeywordAbility('modular',        KeywordAbility::TRIGGER_ABILITY, true); //também STATIC_ABILITY?????

$database = json_encode($ability_list);

$fp = fopen('database\keyword_abilities.json', 'w');
fwrite($fp, $database);
fclose($fp);

class KeywordAbility {

  CONST TRIGGER_ABILITY = 'trigger';
  CONST ACTIVATED_ABILITY = 'activated';
  CONST STATIC_ABILITY = 'static';

  public $name;
  public $type;
  public $value;
  public $cost;
  public $reminder = '';

  public function __construct($name, $type, $value = false, $cost = false) {
    $this->name = $name;
    $this->type = $type;
    $this->value = $value;
    $this->cost = $cost;
  }
}

die('OK');
