<?php
header('Content-Type: text/html; charset=utf-8');

echo 'Inicio<hr>';

require_once ('class.decklist.php');


$creature_type_list   = json_decode(file_get_contents('database\creature_types.json'));
$keyword_ability_list = json_decode(file_get_contents('database\keyword_abilities.json'));

//$arquivo = str_replace('Æ', 'Ae', file_get_contents('database\AllCards-x.json'));
$arquivo = str_replace('Æ', 'Ae', file_get_contents('database\AllCards-x.json'));
$arquivo = trim(preg_replace(['/\s\(([^()]+)\)/i', '/\(([^()]+)\)/i'], '', $arquivo));
$database = json_decode($arquivo);
//$decklist = file('decks\HugeElfBoy_8461535.txt');
$decklist = file('decks\TESTE123.txt');

$deck = new DeckList();

foreach ($decklist as $linha) {
  $linha = trim($linha);
  if (empty($linha)) { break; }
  $qtd = trim(substr($linha, 0, strpos($linha, ' ')));
  $nome = trim(substr($linha, strpos($linha, ' ') + 1));
  if (isset($database->$nome)) {
    $card = new Card($database->$nome);
    $deck->addMainDeckCard(new CardListed($card, $qtd));
  } else {
    die('Nao achou ' . $nome);
  }
}

//$keyword_array = [];
//foreach ($keyword_ability_list as $keyword_ability) {
//  $keyword_array[] = $keyword_ability->name;
//}

foreach ($deck->maindeck as $listed_card) {
  if (isset($card->types) && !in_array('Creature', $listed_card->card->types)) { continue; }

  $text = trim(strtolower($listed_card->card->text));

  while (!empty($text)) {
    $achou = false;

    foreach ($keyword_ability_list as $keyword_ability) { //echo '<br>PROCURANDO '.$keyword_ability->name;
      $tam = strlen($keyword_ability->name);
      if (substr($text, 0, $tam) == $keyword_ability->name) {
        $next_chr = ord(substr($text, $tam, 1));
        if ($keyword_ability->value && $next_chr == ord(' ')) {
          die('CASO 1');
        } else if ($keyword_ability->cost && $next_chr == ord(' ')) {
          die('CASO 2');
        } else if (($keyword_ability->cost || $keyword_ability->value) || (!empty(trim(substr($text, $tam))) && !in_array($next_chr, [10, 13, 44]))) {
          continue;
        } //echo ' - ACHOU!!';

        $listed_card->card->keyword[] = $keyword_ability->name;

        $achou = true;
        $text = trim(substr($text, $tam));

        if (!empty($text) && ord($text[0]) == 44) { //Quebras de linha ou virgula
          $text = trim(substr($text, 1));
        }
        break;
      }
    }

    if ($achou) { continue; }

    $search = 'as an additional cost to cast';

    $tam = strlen($search) + 1;

    if (substr($text, 0, $tam) == $search . ' ') {
      $text = trim(substr($text, $tam));
      foreach (['$', 'creature spells'] as $castto) {
        $tam = strlen($castto) + 2;
        if (substr($text, 0, $tam) == $castto . ', ') {
          $castto_found = $castto;
          $text = trim(substr($text, $tam));
        }
      }
      if (empty($castto_found)) {
        die('SEM ALVO ADDITIONAL COST');
      }
      $fim = strpos($text, '.');
      $custo_adicional = substr($text, 0, $fim);
      $text = trim(substr($text, strlen($custo_adicional) + 1));
      $listed_card->card->additional_cost[$castto] = $custo_adicional;
      continue;
    }

    foreach (['when', 'whenever', 'as'] as $trigger_text) {
      $tam = strlen($trigger_text) + 1;
      if (substr($text, 0, $tam) == $trigger_text . ' ') {
//        die('ACHOU '.$trigger_text);
      }
    }

    echo '<pre>';
    echo '<b>'.$listed_card->card->name.'</b><br>';
    print_r($text);
    echo '<hr></pre>';
    break;
  }
}


die('<br>FIM');
