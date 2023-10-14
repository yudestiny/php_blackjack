<?php

namespace BlackJack;

require_once('Player.php');

class Rule {
  public function __construct (public $usedCards) {
  }

  public int $result  = 0;
  public int $bet = 50;
  private string $firstChoice = '';


  public function choiceRule ($playerCards, $name) {
    $player = new Player($this->usedCards);
    $playerCardRank = $player->getCardRank($playerCards);
    echo $name . 'さん、現在' . $playerCardRank . '点です。行動を選択してください。'.PHP_EOL;
    echo '(ヒット: H , スタンド: S ';
    if (empty($this->firstChoice)) {
      echo ', ダブルダウン: D , ';
    }
        if (empty($this->firstChoice) && in_array(2, array_count_values(array_map(fn ($card) => substr($card, 1, strlen($card) - 1), $playerCards)))) {
          echo 'スプリット: P , ';
        }
        if (empty($this->firstChoice)) {
          echo 'サレンダー: R ';
        }
          echo ')  選択:';
        $rule = trim(fgets(STDIN));
        if ($rule === 'H' || $rule === 'h' ) {
      return  $this->hit ($playerCards, $name);
        } elseif ($rule === 'S' || $rule === 's' ) {
      return  $this->stand ($playerCards);
        } elseif (empty($this->firstChoice) && $rule === 'D' || empty($this->firstChoice) && $rule === 'd') {
      return  $this->doubleDown ($playerCards, $name);
    } elseif (empty($this->firstChoice) && $rule === 'P' || empty($this->firstChoice) && $rule === 'p') {
      return  $this->split($playerCards, $name);
    } elseif (empty($this->firstChoice) && $rule === 'R' || empty($this->firstChoice) && $rule === 'r') {
      return  $this->surrender($playerCards);
    }
      echo '正しい選択肢を選んでください' . PHP_EOL;
      return $this->choiceRule($playerCards, $name);
    }

    public function discardCards($cards) {

    foreach ($cards as $card) {

        if(!in_array($card, $this->usedCards)) {
        $this->usedCards[] = $card;
      }
    }
      }

  public function hit ($playerCards, $name):array {
    echo 'ヒットですね' . PHP_EOL;
    $this->firstChoice = 'no';
    $player = new Player($this->usedCards);
      $playerCards[] = $player->getAdditionalCards();
      $this->discardCards($playerCards);
      $playerCardRank = $player->getCardRank($playerCards);
      echo $name . 'さんが引いたカードは' . $playerCards[count($playerCards) - 1] . 'でした。現在のカードのスコアは' . $playerCardRank . '点となりました。' . PHP_EOL;
      if (Judge::judgeOver21($playerCardRank)) {
        return $playerCards;
      }
      return $this->choiceRule($playerCards, $name);

    }

  public function stand ($playerCards) {
    echo 'スタンドですね' . PHP_EOL;
    return $playerCards;
  }

  public function doubleDown ($playerCards, $name) {
    echo 'ダブルダウンですね'.PHP_EOL;
    $player = new Player($this->usedCards);
    $playerCards[] = $player->getAdditionalCards();
    $playerCardRank = $player->getCardRank($playerCards);
    echo $name . 'さんが引いたカードは' . $playerCards[count($playerCards) - 1] . 'でした。現在のカードのスコアは' . $playerCardRank . '点となりました。' . PHP_EOL;
    $this->bet = $this->bet * 2;
    return $playerCards;
  }

  public function split ($playerCards, $name) {
    echo 'スプリットですね' . PHP_EOL;

      foreach ($playerCards as $playerCard) {
        $cards[] = $this->choiceRule([$playerCard], $name);
      }
      return $cards;
  }

  public function surrender ($playerCards) {
    echo 'サレンダーですね' . PHP_EOL;
    $this->bet = $this->bet * 2;
    return [];

  }

  // public function getResult ($result) {
  //   if ($result === 'win') {
  //     return $this->result += $this->bet;
  //   } elseif ($result === 'draw') {
  //     return $this->result = 0;
  //   } elseif ($result === 'lose') {
  //     return $this->result -= $this->bet;
  //   }
  // }

    }
