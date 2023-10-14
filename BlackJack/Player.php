<?php

namespace BlackJack;
require_once('Card.php');

class Player
{
  public function __construct (public $usedCards) {

  }
  public function getFirstCards():array
  {
      $cards = [];
      $card = new Card($this->usedCards);
        $cards[] = $card->getCard();
        $cards[] = $card->getCard();
        $game = new BJGame();
        foreach($cards as $card) {
          $game->usedCards[] = $card;
        }

      return $cards;
  }
  public function getCardRank(array$playerCard):int
  {
    $card = new Card($this->usedCards);

    return array_sum($card->getRank($playerCard));
  }

  public function getAdditionalCards():string
  {
      $card = new Card($this->usedCards);
      $additionalCard = $card->getCard();
    return $additionalCard;
  }


}
