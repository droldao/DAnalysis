<?php
header('Content-Type: text/html; charset=utf-8');

$creature_type_list = json_decode(file_get_contents('database\creature_types.json'));

$ability_list = [];

$ability_list[] = new KeywordAbility('flying');
$ability_list[] = new KeywordAbility('vigilance');
$ability_list[] = new KeywordAbility('flash');
$ability_list[] = new KeywordAbility('first strike');
$ability_list[] = new KeywordAbility('double strike');
$ability_list[] = new KeywordAbility('trample');
$ability_list[] = new KeywordAbility('delve');
$ability_list[] = new KeywordAbility('deathtouch');
$ability_list[] = new KeywordAbility('lifelink');
$ability_list[] = new KeywordAbility('haste');
$ability_list[] = new KeywordAbility('prowess');
$ability_list[] = new KeywordAbility('indestructible');
$ability_list[] = new KeywordAbility('battle cry');
$ability_list[] = new KeywordAbility('swampwalk');
$ability_list[] = new KeywordAbility('islandwalk');
$ability_list[] = new KeywordAbility('plainswalk');
$ability_list[] = new KeywordAbility('mountainwalk');
$ability_list[] = new KeywordAbility('forestwalk');
$ability_list[] = new KeywordAbility('nonbasic landwalk');
$ability_list[] = new KeywordAbility('snow landwalk');
$ability_list[] = new KeywordAbility('legendary landwalk');
$ability_list[] = new KeywordAbility('reach');
$ability_list[] = new KeywordAbility('defender');
$ability_list[] = new KeywordAbility('soulbond');
$ability_list[] = new KeywordAbility('hexproof');
$ability_list[] = new KeywordAbility('shadow');
$ability_list[] = new KeywordAbility('flanking');
$ability_list[] = new KeywordAbility('fear');
$ability_list[] = new KeywordAbility('unleash');
$ability_list[] = new KeywordAbility('extort');
$ability_list[] = new KeywordAbility('shroud');
$ability_list[] = new KeywordAbility('evolve');
$ability_list[] = new KeywordAbility('exalted');
$ability_list[] = new KeywordAbility('menace');
$ability_list[] = new KeywordAbility('infect');
$ability_list[] = new KeywordAbility('persist');
$ability_list[] = new KeywordAbility('sunburst');
$ability_list[] = new KeywordAbility('convoke');
$ability_list[] = new KeywordAbility('wither');
$ability_list[] = new KeywordAbility('undying');
$ability_list[] = new KeywordAbility('haunt');
$ability_list[] = new KeywordAbility('split second');
$ability_list[] = new KeywordAbility('intimidate');
$ability_list[] = new KeywordAbility('changeling');
$ability_list[] = new KeywordAbility('cascade');
$ability_list[] = new KeywordAbility('exploit');

$ability_list[] = new KeywordAbility('hideaway');
$ability_list[] = new KeywordAbility('totem armor');
$ability_list[] = new KeywordAbility('living weapon');
$ability_list[] = new KeywordAbility('vanishing');


foreach ($creature_type_list as $type) {
 $ability_list[] = new KeywordAbility(strtolower($type) . ' offering');
}

$ability_list[] = new KeywordAbility('annihilator', KeywordAbility::KEYWORD_WITH_VALUE);

$ability_list[] = new KeywordAbility('rampage', KeywordAbility::KEYWORD_WITH_VALUE);
$ability_list[] = new KeywordAbility('tribute', KeywordAbility::KEYWORD_WITH_VALUE);
$ability_list[] = new KeywordAbility('bloodthirst', KeywordAbility::KEYWORD_WITH_VALUE);
$ability_list[] = new KeywordAbility('bushido', KeywordAbility::KEYWORD_WITH_VALUE);
$ability_list[] = new KeywordAbility('soulshift', KeywordAbility::KEYWORD_WITH_VALUE);
$ability_list[] = new KeywordAbility('dredge', KeywordAbility::KEYWORD_WITH_VALUE);
$ability_list[] = new KeywordAbility('vanishing', KeywordAbility::KEYWORD_WITH_VALUE);
$ability_list[] = new KeywordAbility('graft', KeywordAbility::KEYWORD_WITH_VALUE);
$ability_list[] = new KeywordAbility('ripple', KeywordAbility::KEYWORD_WITH_VALUE);
$ability_list[] = new KeywordAbility('renown', KeywordAbility::KEYWORD_WITH_VALUE);
$ability_list[] = new KeywordAbility('devour', KeywordAbility::KEYWORD_WITH_VALUE);

$ability_list[] = new KeywordAbility('scavenge', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('megamorph', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('dash', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('bestow', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('outlast', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('ninjutsu', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('echo', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('echo', KeywordAbility::KEYWORD_WITH_COST, '—');
$ability_list[] = new KeywordAbility('unearth', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('kicker', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('multikicker', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('evoke', KeywordAbility::KEYWORD_WITH_COST);

$ability_list[] = new KeywordAbility('swampcycling', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('plainscycling', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('mountaincycling', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('islandcycling', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('forestcycling', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('basic landcycling', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('transmute', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('madness', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('suspend', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('reinforce', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('level up', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('transfigure', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('prowl', KeywordAbility::KEYWORD_WITH_COST);

$ability_list[] = new KeywordAbility('cumulative upkeep', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('cumulative upkeep', KeywordAbility::KEYWORD_WITH_COST, '—');

$ability_list[] = new KeywordAbility('morph', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('morph', KeywordAbility::KEYWORD_WITH_COST, '—');

$ability_list[] = new KeywordAbility('cycling', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('cycling', KeywordAbility::KEYWORD_WITH_COST, '—');

$ability_list[] = new KeywordAbility('equip', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('equip', KeywordAbility::KEYWORD_WITH_COST, '—');

$ability_list[] = new KeywordAbility('modular', KeywordAbility::KEYWORD_WITH_COST);
$ability_list[] = new KeywordAbility('modular', KeywordAbility::KEYWORD, '—');

$ability_list[] = new KeywordAbility('protection', KeywordAbility::KEYWORD_WITH_OBJECT, ' from ', true);
$ability_list[] = new KeywordAbility('affinity', KeywordAbility::KEYWORD_WITH_OBJECT, ' for ');
$ability_list[] = new KeywordAbility('champion', KeywordAbility::KEYWORD_WITH_OBJECT, ' a ');
$ability_list[] = new KeywordAbility('champion', KeywordAbility::KEYWORD_WITH_OBJECT, ' an ');
$ability_list[] = new KeywordAbility('enchant', KeywordAbility::KEYWORD_WITH_OBJECT);

$ability_list[] = new KeywordAbility('metalcraft', KeywordAbility::KEYWORD_DELETE);
$ability_list[] = new KeywordAbility('threshold', KeywordAbility::KEYWORD_DELETE);
$ability_list[] = new KeywordAbility('constellation', KeywordAbility::KEYWORD_DELETE);
$ability_list[] = new KeywordAbility('inspired', KeywordAbility::KEYWORD_DELETE);
$ability_list[] = new KeywordAbility('raid', KeywordAbility::KEYWORD_DELETE);
$ability_list[] = new KeywordAbility('heroic', KeywordAbility::KEYWORD_DELETE);
$ability_list[] = new KeywordAbility('kinship', KeywordAbility::KEYWORD_DELETE);
$ability_list[] = new KeywordAbility('domain', KeywordAbility::KEYWORD_DELETE);
$ability_list[] = new KeywordAbility('morbid', KeywordAbility::KEYWORD_DELETE);
$ability_list[] = new KeywordAbility('hellbent', KeywordAbility::KEYWORD_DELETE);
$ability_list[] = new KeywordAbility('ferocious', KeywordAbility::KEYWORD_DELETE);
$ability_list[] = new KeywordAbility('chroma', KeywordAbility::KEYWORD_DELETE);
$ability_list[] = new KeywordAbility('imprint', KeywordAbility::KEYWORD_DELETE);
$ability_list[] = new KeywordAbility('landfall', KeywordAbility::KEYWORD_DELETE);
$ability_list[] = new KeywordAbility('battalion', KeywordAbility::KEYWORD_DELETE);

$database = json_encode($ability_list);

$fp = fopen('database\keyword_abilities.json', 'w');
fwrite($fp, $database);
fclose($fp);

class KeywordAbility {

  CONST KEYWORD = 'simple';
  CONST KEYWORD_WITH_COST = 'cost';
  CONST KEYWORD_WITH_VALUE = 'value';
  CONST KEYWORD_WITH_OBJECT = 'object';
  CONST KEYWORD_DELETE = 'delete';

  public $name;
  public $type;
  public $multi;
  public $text_link;

  public function __construct($name, $type = self::KEYWORD, $text_link = '', $multi = false) {
    $this->name = $name;
    $this->type = $type;
    $this->multi = $multi;

    if ($type == self::KEYWORD_DELETE) {
      $this->text_link = ' —';
    } else if (empty($text_link)) {
      $this->text_link = ' ';
    } else {
      $this->text_link = $text_link;
    }
  }
}

die('OK');
