<?php

namespace BlackJack;

require_once('Card.php');

class Dealer
{
  public function __construct(public $usedCards)
  {
  }
  public function getCards()
  {
    $cards = [];
    $card = new Card($this->usedCards);
      $cards[] = $card->getCard();
      $cards[] = $card->getCard();
    return $cards;
  }
  public function getCardRank($dealerCard)
  {
    $card = new Card($this->usedCards);
$result = $card->getRank($dealerCard);
    
    return $result;
}
}
