<?php
header('Content-Type: text/html; charset=utf-8');

define("EM_DASH", "[");
define("BULLET", "]");
define("AE", "Ae");

define("CHR_EOL", 10);
define("CHR_CR", 13);
define("CHR_COMMA", 44);
/*
$opts = array('http' => array('header' => 'Accept-Charset: UTF-8, *;q=0'));
$context = stream_context_create($opts);
file_get_contents('database\AllCards-x.json', false, $context)
 */

echo 'Inicio<hr>';

require_once ('class.decklist.php');

$creature_type_list   = json_decode(file_get_contents('database\creature_types.json'));
$keyword_ability_list = json_decode(file_get_contents('database\keyword_abilities.json'));

$arquivo = str_replace(['Æ', '—', '•'], [AE, EM_DASH, BULLET], file_get_contents('database\AllCards-x.json'));
//$arquivo = file_get_contents('database\AllCards-x.json');
$arquivo = trim(preg_replace(['/\s\(([^()]+)\)/i', '/\(([^()]+)\)/i'], '', $arquivo));
$database = json_decode($arquivo);

//$arquivo = str_replace([utf8_encode(chr(198))], ['Ae'], file_get_contents('database\AllCards-x.json'));
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
        if (!empty($keyword_ability->type) && $next_chr == ord(' ')) {
          $text = trim(substr($text, $tam));

          if ($keyword_ability->type == 'delete') {
            $text = trim(substr($text, 2));
            $achou = true;
            break;
          }

          // O que vier primeiro
          $come_first = ([strpos($text, CHR_EOL), strpos($text, CHR_CR), strpos($text, CHR_COMMA), strlen($text)]);
          $come_first = array_filter($come_first);
          sort($come_first);
          $max = $come_first[0];
          $key_word_det = substr($text, 0, $max);
          $text = trim(substr($text, $max + 1));

          $listed_card->card->keyword[] = [$keyword_ability->name => $key_word_det];

          $achou = true;
          break;
        } else if (!empty($keyword_ability->type) || (!empty(trim(substr($text, $tam))) && !in_array($next_chr, [CHR_EOL, CHR_CR, CHR_COMMA]))) {
          die('ERRO - 1.1');
          continue;
        } else {
          $listed_card->card->keyword[] = $keyword_ability->name;

          $text = trim(substr($text, $tam));

          if (!empty($text) && ord($text[0]) == CHR_COMMA) { //Quebras de linha ou virgula
            $text = trim(substr($text, 1));
          }
          $achou = true;
          break;
        }
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
          break;
        }
      }
      if (empty($castto_found)) {
        die('ERRO - CASO 2.1');
      }
      $fim = strpos($text, '.');
      $custo_adicional = substr($text, 0, $fim);
      $text = trim(substr($text, strlen($custo_adicional) + 1));
      $listed_card->card->additional_cost[$castto] = $custo_adicional;
      continue;
    }

    foreach (['when', 'whenever', 'as', 'if'] as $trigger_text) {
      if (substr($text, 0, (strlen($trigger_text) + 1)) == $trigger_text . ' ') {
        $achou = true;
        $condition = substr($text, 0, strpos($text, ','));
        $text = trim(substr($text, strlen($condition) + 1));

        $search = 'choose one';

        if (substr($text, 0, (strlen($search) + 2)) == $search . ' ' . EM_DASH) {
          $text = substr($text, strlen($search) + 3);
          $effects = [];
          while (!empty($text) && $text[0] == BULLET) {
            $effect = trim(substr($text, 1, strpos($text, '.')));
            $text = trim(substr($text, strlen($effect) + 2));
            $effects[] = $effect;
          }
          $listed_card->card->trigger[$condition] = $effects;
        } else {
          $effect = substr($text, 0, strpos($text, '.'));
          $text = substr($text, strlen($effect) + 1);
          if ($text[0] == ' ') {
            $effect_pos = substr($text, 1, strpos($text, '.'));
            $text = substr($text, strlen($effect_pos) + 1);
            if ($text[0] == ' ') {
              $effect_cond_pos = substr($text, 1, strpos($text, '.'));
              $text = substr($text, strlen($effect_cond_pos) + 1);
              $effect_pos .= $effect_cond_pos;
              if ($text[0] == ' ') {
                die('ERRO - CASO 3.1');
              }
            }
            $listed_card->card->trigger[$condition] = [$effect, $effect_pos];
          } else {
            $text = trim($text);
            $listed_card->card->trigger[$condition] = $effect;
          }
          $achou = true;
          break;
        }
      }
    }

    if ($achou) { continue; }

    foreach (['you', 'your opponents', 'players'] as $trigger_text) {
      if (substr($text, 0, (strlen($trigger_text) + 1)) == $trigger_text . ' ') {
        foreach (['can', 'can\'t', 'may'] as $trigger_cond) {
          if (substr($text, (strlen($trigger_text) + 1), (strlen($trigger_cond) + 1)) == $trigger_cond . ' ') {
            $trigger_cond_found = $trigger_text.' '.$trigger_cond;
          }
        }
        if (empty($trigger_cond_found)) {
          die('ERRO - CASO 4.1');
        }

        $text = trim(substr($text, strlen($trigger_cond_found) + 1));
        $effect = substr($text, 0, strpos($text, '.'));
        $text = substr($text, strlen($effect) + 1);

        if ($text[0] == ' ') {
          $effect_pos = substr($text, 1, strpos($text, '.'));
          $text = substr($text, strlen($effect_pos) + 1);
          if ($text[0] == ' ') {
            $effect_cond_pos = substr($text, 1, strpos($text, '.'));
            $text = substr($text, strlen($effect_cond_pos) + 1);
            $effect_pos .= $effect_cond_pos;
            if ($text[0] == ' ') {
              die('ERRO - CASO 4.1');
            }
          }
          $listed_card->card->static[$trigger_cond_found] = [$effect, $effect_pos];
        } else {
          $text = trim($text);
          $listed_card->card->static[$trigger_cond_found] = $effect;
        }
        $achou = true;
        break;
      }
    }


    if ($achou) { continue; }

    $come_first = ([strpos($text, CHR_EOL), strpos($text, CHR_CR), strlen($text), strpos($text, ':')]);
    $come_first = array_filter($come_first);
    sort($come_first);
    $busca = $come_first[0];
    if ($busca == strpos($text,':')) {
      $custo = substr($text, 0, $busca);
      $text = trim(substr($text, strlen($custo) + 2));
      $effect = substr($text, 0, strpos($text, '.'));
      $text = substr($text, strlen($effect) + 1);

      if ($text[0] == ' ') {
        $effect_pos = substr($text, 1, strpos($text, '.'));
        $text = substr($text, strlen($effect_pos) + 1);
        if ($text[0] == ' ') {
          $effect_cond_pos = substr($text, 1, strpos($text, '.'));
          $text = substr($text, strlen($effect_cond_pos) + 1);
          $effect_pos .= $effect_cond_pos;
          if ($text[0] == ' ') {
            die('ERRO - CASO 5.1');
          }
        }
        $listed_card->card->activated[$custo] = [$effect, $effect_pos];
      } else {
        $text = trim($text);
        $listed_card->card->activated[$custo] = $effect;
      }
      $achou = true;
    }

    if ($achou) { continue; }

    $come_first = ([strpos($text, CHR_EOL), strpos($text, CHR_CR), strlen($text), strpos($text, ' have'), strpos($text, ' cost'), strpos($text, ' get')]);
    $come_first = array_filter($come_first);
    sort($come_first);
    $busca = $come_first[0];

    if (in_array($busca, [strpos($text, ' have '), strpos($text, ' cost '), strpos($text, ' get ')])) {
      $grupo_afetado = substr($text, 0, $busca);
      $text = trim(substr($text, strlen($grupo_afetado)));
      $effect = substr($text, 0, strpos($text, '.'));
      $text = trim(substr($text, strlen($effect) + 1));
      $listed_card->card->static[$grupo_afetado] = $effect;
      $achou = true;
    }


    if ($achou) { continue; }

    if ($text[0] == '$') {
      $text = trim(substr($text, 2));
      $effect = substr($text, 0, strpos($text, '.'));
      $text = trim(substr($text, strlen($effect) + 1));

      $listed_card->card->static['$'][] = $effect;
      $achou = true;
    }

    if ($achou) { continue; }

//    break;
    echo '<pre>';
    echo '<b>'.$listed_card->card->name.'</b><br>';
    print_r($text);
    echo '<hr></pre>';
    break;
  }
}
//die('<br>FIM');
foreach ($deck->maindeck as $listed_card) {
  echo '<b>'.$listed_card->card->name.'</b><br>';
  echo '<pre>';
  print_r($listed_card->card->keyword);
  print_r($listed_card->card->additional_cost);
  print_r($listed_card->card->trigger);
  print_r($listed_card->card->static);
  echo '</pre>';
}
die('<br>FIM');
