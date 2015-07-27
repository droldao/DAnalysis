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

  CONST PLAYER_TAG = '[THIS]';
  CONST COST_TAG = '[COST]';

  public $protections = ['protection from black and from red'];
  public $abilities = ['keyword' => [], 'trigger' => [], 'static' => [], 'actived' => []];
  public $keyword_abilities = ['flying', 'vigilance', 'flash', 'first strike', 'trample', 'haste'];
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

      echo '<hr>';
      var_dump($this->text);
      echo '</pre>';
    }
  }

  public function find_abilities() {
    if (empty($this->text)) {
      return;
    }
    $this->find_keywords();
    if (empty($this->text)) {
      return;
    }
    $this->find_triggers();
  }

  public function find_triggers() {
    $triggers_search = [];
    foreach ($this->triggered_abilities as $trigger) {
      $triggers_search[] = '/' . ucfirst($trigger) . '\s/';
    }

    $callback = function ($matches) {
      $trigger_name = trim(strtolower($matches[0]));

      $this->abilities['trigger'][] = $trigger_name;
      return '[' . strtoupper($trigger_name) . ']';
    };

    $this->text = trim(preg_replace_callback($triggers_search, $callback, $this->text));
  }

  public function find_keywords() {
    $keyword_search = [];
    foreach (array_merge($this->keyword_abilities, $this->protections) as $keyword) {
      $keyword_search[] = '/' . ucfirst($keyword) . '/';
      $keyword_search[] = '/,\s' . $keyword . '/';
    }
    $callback = function ($matches) {
      $ability_name = trim(strtolower($matches[0]));

      if (strpos($ability_name, ',') !== false) {
        $ability_name = trim(substr($ability_name, strpos($ability_name, ',') + 1));
      }
      $this->abilities['keyword'][] = $ability_name;
      return '';
    };
    $this->text = trim(preg_replace_callback($keyword_search, $callback, $this->text));
  }

  public function text_adaptation() {
    if (!isset($this->text)) {
      $this->text = '';
    }

    $this->remove_reminders();
    $this->selfname_replace();
  }

  public function remove_reminders() {
    $callback = function ($matches) {
      return '';
    };
    $this->text = trim(preg_replace_callback('/\s\(([^()]+)\)/i', $callback, $this->text));
  }

  public function selfname_replace() {
    $name_appearances = ['/' . $this->name . '/i'];

    $name = trim(substr($this->name, 0, strpos($this->name, ',')));

    if (!empty($name)) {
      $name_appearances[] = '/' . $name . '/i';
    }

    $callback = function ($matches) {
      return self::PLAYER_TAG;
    };


    $this->text = preg_replace_callback($name_appearances, $callback, $this->text);


//    if (!empty($selfname_replace)) {
//      echo '<pre>selfname_replace';
//      print_r($selfname_replace);
//      echo '</pre>';
//    }
  }

}
