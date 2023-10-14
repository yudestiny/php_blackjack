<?php

namespace BlackJack;


class Card
{
  private $cards = [
    'A' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
    'H' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
    'C' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
    'S' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
  ];

  private const MARK = ['A', 'H', 'C', 'S'];

  private const CARD_RANK = [
    '1' => '1',
    '2' => '2',
    '3' => '3',
    '4' => '4',
    '5' => '5',
    '6' => '6',
    '7' => '7',
    '8' => '8',
    '9' => '9',
    '10' =>'10',
    '11' => '10',
    '12' => '10',
    '13' => '10',
  ];

  public function __construct(public $usedCards)
  {
  }

  public function getCard():string
  {
    while (true) {
      $mark = self::MARK[mt_rand(0,3)] ;
      $num = mt_rand(0, count($this->cards[$mark])-1);
        $card = $this->cards[$mark][$num];
      if (!in_array($mark . $card, $this->usedCards)){
        array_splice($this->cards[$mark], $num, 1);
        return $mark. $card;
      }

    }


  }
  public function getRank(array $cards):array
  {
    $cardNums = array_map(fn($card) => substr($card, 1, strlen($card)-1),$cards);
    $cardScores = [];
    foreach ($cardNums as $cardNum) {
      $cardScores[] = self::CARD_RANK[$cardNum];
      }
      if(in_array('1', $cardScores) && array_sum($cardScores) <= 11) {
      $cardScores[array_keys($cardScores, '1')[0]] = '11';
      }
    return $cardScores;
  }
}
