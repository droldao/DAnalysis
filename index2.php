<?php
header('Content-Type: text/html; charset=utf-8');

$arquivo = file_get_contents('AllCards-x.json');

$dados = json_decode($arquivo);

$all_names = array_keys(get_object_vars($dados));
$all_names = array_reverse($all_names);

echo '<pre>';

foreach ($all_names as $nome) {
  $card_dados = $dados->$nome;

  if ($card_dados->legalities->Modern != 'Legal') {
    continue;
  }

  if ($card_dados->cmc != 2) {
    continue;
  }

  $card = new card($card_dados);

  $card->avaliar();

  if ($card->eh_criatura) {
    $card->resumo();
  }

  $cont++;
  if ($cont > 185 ){
    break;
  }
}

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
      $pos_fim = strpos(substr($texto, $pos_ini + 1), ' ');
      $valor = substr($texto, $pos_ini + 1, $pos_fim);
      $texto = substr_replace($texto, '', $pos, strlen($nome_habilidade) + 1 + $pos_fim);
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

?>

