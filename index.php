<?php
echo 'Inicio<hr>';

require_once ('class.decklist.php');


$arquivo_1 = file_get_contents('database\AllCards-x.json');
$database = json_decode($arquivo_1);
$decklist = file('decks\TESTE123.txt');

//echo '<pre>';
//print_r($database->{'Aven Mindcensor'});
//echo '</pre>';
//die('DEBUG');

$deck = new DeckList();

foreach ($decklist as $linha) {

  $linha = trim($linha);

  if (empty($linha)) {
    break;
  }

  $qtd = trim(substr($linha, 0, strpos($linha, ' ')));
  $nome = trim(substr($linha, strpos($linha, ' ') + 1));

  if (isset($database->$nome)) {
    $card = new Card($database->$nome);
    $deck->addMainDeckCard(new CardListed($card, $qtd));
  } else {
    //die('Nao achou '.$nome);
  }
}

//foreach ($deck->maindeck as $listed_card) {
//  echo '<pre>';
//  print_r($listed_card->card);
//  echo '</pre>';
//  die('DEBUG');
//}
//preg_match()

die('<br>FIM');
