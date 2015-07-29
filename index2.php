<?php

header('Content-Type: text/html; charset=utf-8');

$arquivo = file_get_contents('database\AllCards-x.json');

$dados = json_decode($arquivo);

$all_names = array_keys(get_object_vars($dados));

echo '<pre>';
$tipos = array();

foreach ($all_names as $nome) {
  $card_dados = $dados->$nome;

  if ($card_dados->legalities->Modern != 'Legal') {
    continue;
  }
  if (!in_array('Creature', $card_dados->types)) {
    continue;
  }

  while (($pos_inicio_reminder = strpos($card_dados->text, '(')) !== false) {
    $pos_fim_reminder = strpos($card_dados->text, ')', $pos_inicio_reminder);
    $texto_reminder = substr($card_dados->text, $pos_inicio_reminder, $pos_fim_reminder+1-$pos_inicio_reminder);

    $card_dados->text = substr_replace($card_dados->text, '', $pos_inicio_reminder, $pos_fim_reminder+1-$pos_inicio_reminder);
    $key_word = substr($card_dados->text, 0, $pos_inicio_reminder);
    echo '<pre><hr>'.$key_word. '<br>';
print_r($texto_reminder);
echo '</pre>';

  }

  $card_dados->text = trim($card_dados->text);
  
  $tipos = array_merge($tipos, $card_dados->subtypes);
  $tipos = array_unique($tipos);
}

sort($tipos);

die('--DEBUG FARIAS');
/*
  class card {

  public $card;
  public $texto;
  public $conjuracao;
  public $valor;
  public $power_level;
  public $keywords = array();

  public function __construct($card) {
  $this->card = $card;
  $this->eh_criatura = in_array('Creature', $card->types);
  $this->valor = 0;

  $this->tratar_texto();
  }

  public function avaliar() {
  if ($this->eh_criatura) {
  $this->avaliarCriatura();
  }
  }

  public function tratar_texto() {
  $text = $this->card->text;

  //REMOVE O NOME E COLOCA THIS
  $text = str_replace(array($this->card->name.'\'s', $this->card->name, reset(explode(',', $this->card->name))), 'THIS', $text);


  $text = str_replace('enters the battlefield', 'ETB', $text);

  //REMOVE OS REMINDER TEXTS
  $this->texto = preg_replace('/\(([^\)]+)\)/','', $text);
  }

  public function avaliarCriatura() {
  $this->avaliar_custo();

  $this->identificarHabilidades();

  $this->valor += $this->card->power * 5;
  $this->valor += $this->card->toughness * 1.5;

  $this->power_level = ($this->valor / $this->conjuracao) * 100;
  }

  public function identificarHabilidades() {
  $procurar = array('renown' => true,
  'flying' => false,
  'vigilance' => false,
  'prowess' => false,
  'first strike' => false,
  'megamorph' => true,
  );

  foreach ($procurar as $keyword => $possui_valor) {
  $this->procurar_keyword($keyword, $possui_valor);
  }
  }

  public function procurar_keyword($nome_habilidade, $possui_valor = false) {
  $texto = $this->texto;

  $pos = strpos($texto, ucfirst($nome_habilidade));

  if ($pos === false) {
  $pos = strpos($$texto, $nome_habilidade);
  }

  if ($pos === false) {
  return;
  }

  if ($possui_valor) {
  $pos_ini = $pos + strlen($nome_habilidade);
  $pos_fim_reminder = strpos(substr($texto, $pos_ini + 1), ' ');
  $valor = substr($texto, $pos_ini + 1, $pos_fim_reminder);
  $texto = substr_replace($texto, '', $pos, strlen($nome_habilidade) + 1 + $pos_fim_reminder);
  } else {
  $texto = substr_replace($texto, '', $pos, strlen($nome_habilidade));
  }

  $this->texto = trim($texto);

  if ($possui_valor) {
  $this->adicionar_keyword($nome_habilidade, $valor);
  } else {
  $this->adicionar_keyword($nome_habilidade);
  }
  }

  public function adicionar_keyword($nome_habilidade, $valor = '') {
  $this->keywords[ucfirst($nome_habilidade)] = $valor;
  }


  public function avaliar_custo() {
  $custo = str_replace('{', '', $this->card->manaCost);
  $this->custo = explode('}', substr($custo, 0, -1));

  $this->conjuracao = $this->card->cmc * 10;
  }

  public function resumo() {
  echo '<hr>';
  echo round($this->power_level).' % ';
  echo '<b>'.$this->card->name.'</b> - '.$this->card->power.'/'.$this->card->toughness.' - '.implode(' | ', array_keys($this->keywords)).'<br>';
  if (!empty($this->texto)) {
  echo '> '.$this->texto;
  }
  }
  }
 */
?>

