<?php

class DeckList {

  public $maindeck = [];
  public $sideboard = [];

  public function addMainDeckCard(CardListed $card) {
    $this->maindeck[] = $card;
  }

  public function addSideboardCard(CardListed $card) {
    $this->sideboard[] = $card;
  }

}

class CardListed {

  public $qtd;
  public $card;

  public function __construct(Card $card, $qtd) {
    $this->qtd = $qtd;
    $this->card = $card;
  }

}

class Card {

  public $abilities = ['keyword' => [], 'trigger' => [], 'static' => [], 'actived' => []];
  public $protections = ['protection from black and from red', 'protection from red', 'protection from colored spells'];
  public $keyword_abilities = ['flying' => '', 'vigilance' => '', 'flash' => '', 'first strike' => '', 'trample' => '', 'haste' => '', 'delve' => '',
      'deathtouch' => '', 'lifelink' => '', 'prowess' => '', 'indestructible' => '', 'battle cry' => '',
      'annihilator' => 'X', 'modular' => 'X', 'battle cry' => 'X'];
  public $triggered_abilities = ['when', 'whenever', 'at'];

  public function __construct($card_obj) {
    foreach (get_object_vars($card_obj) as $campo => $valor) {
      $this->$campo = $valor;
    }

    $this->is_noncreature = !in_array('Creature', $this->types);
    $this->is_creature = !$this->is_noncreature;

    $this->text_adaptation();

    if ($this->is_creature) {
      $this->find_abilities();

      if (!empty($this->text)) {
        echo '<hr>';
        var_dump($this->text);
        echo '</pre>';
      }
    }
  }

  public function find_abilities() {
    if (empty($this->text)) {
      return;
    }
    $this->search_keywords();
    if (empty($this->text)) {
      return;
    }
    $this->search_triggers();
    if (empty($this->text)) {
      return;
    }
    $this->search_actived_abilities();
  }

  public function search_actived_abilities() {
    $actived_abilities_search = '/([^\n]+)\:\s([^\n]+)/';
    $this->text = trim(preg_replace_callback($actived_abilities_search, [$this, 'find_actived_abilities'], $this->text));
  }

  public function search_triggers() {
    $triggers_search = [];

    foreach ($this->triggered_abilities as $trigger) {
      $triggers_search[] = '/' . ucfirst($trigger) . '\s([^\n]+)/';
    }

    $this->text = trim(preg_replace_callback($triggers_search, [$this, 'find_trigger'], $this->text));
  }

  public function search_keywords() {
    $keyword_search = [];

    foreach (array_merge($this->keyword_abilities, array_flip($this->protections)) as $keyword => $value) {
      $compl = $value !== 'X' ? '' : '\s([^\nA-Z,]+)';
      $keyword_search[] = '/' . ucfirst($keyword) . $compl . '/';
      $keyword_search[] = '/,\s' . $keyword . $compl . '/';
    }
    $this->text = trim(preg_replace_callback($keyword_search, [$this, 'find_keyword'], $this->text));
  }

  public function text_adaptation() {
    if (!isset($this->text)) {
      $this->text = '';
    }

    $this->remove_reminders();
    $this->selfname_replace();
  }

  public function remove_reminders() {
    $this->text = trim(preg_replace_callback(['/\s\(([^()]+)\)/i', '/\(([^()]+)\)/i'], [$this, 'find_reminder'], $this->text));
  }

  public function selfname_replace() {
    $name_appearances = ['/' . $this->name . '/i'];

    $name = trim(substr($this->name, 0, strpos($this->name, ',')));

    if (!empty($name)) {
      $name_appearances[] = '/' . $name . '/i';
    }

    $this->text = preg_replace_callback($name_appearances, [$this, 'find_this'], $this->text);
  }

  public function find_this($matches) {
    return 'this';
  }

  public function find_reminder($matches) {
    return '';
  }

  public function find_keyword($matches) {
    $ability_name = trim(strtolower($matches[0]));

    if (strpos($ability_name, ',') !== false) {
      $ability_name = trim(substr($ability_name, strpos($ability_name, ',') + 1));
    }
    $this->abilities['keyword'][] = $ability_name;
    return '';
  }

  public function find_trigger($matches) {
    $trigger_name = $matches[0];
    $this->abilities['trigger'][] = $trigger_name;
    return '';
  }

  public function find_actived_abilities($matches) {
    $actived_ability = $matches[0];
    $this->abilities['actived'][] = $actived_ability;
    return '';
  }

}
