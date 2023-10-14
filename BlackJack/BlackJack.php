<?php

namespace BlackJack;
require_once('Player.php');
require_once('Dealer.php');
require_once('Judge.php');
require_once('Rule.php');

class BJGame
{
    // public function __construct(private ) {

    // }
    public array $usedCards = [];
    public array $resultMoney = [];
  public array $rank = [];

    public function gameStart() {
      $participants = $this->selectPlayersNumber();
      if (empty($this->resultMoney)) {
      $names = $this->getPlayerName($participants);
      } else {
        foreach ($this->resultMoney as $name => $money) {
          $newNames[] = $name;
        }
        foreach ($newNames as $num => $name) {
          $names[$num*2] = $name;
        }
      }
    if (empty($this->resultMoney)) {
      foreach($names as $name) {

        $this->resultMoney[$name] = 100;
      }
    }


      echo '対戦お願いします。'.PHP_EOL;
      echo 'それではひとり2枚ずつカードを引いていきます。'.PHP_EOL;
      $playerCard = [];
    $playerCardRank = [];
    $player = new Player($this->usedCards);
    for ($i = 0; $i < $participants * 2; $i += 2) {
      $playerCard[$i] = $player->getFirstCards();
      $this->discardCards($playerCard[$i]);
      echo $names[$i] . 'さんの引いたカードは' . $playerCard[$i][0] . 'と' . $playerCard[$i][1] . 'です。' . PHP_EOL;

    $playerCardRank[$i] = $player->getCardRank($playerCard[$i]);
      echo $names[$i] . 'さんの現在のカードのスコアは' . $playerCardRank[$i] . '点です。' . PHP_EOL;
    }
      $dealer = new Dealer($this->usedCards);
            $dealerCards = $dealer->getCards();
          $this->discardCards($dealerCards);
      echo '私の引いたカードは' . $dealerCards[0] .  'です。' . PHP_EOL;
      $dealerCardRank = $dealer->getCardRank($dealerCards);
      echo '私の現在のカードのスコアは' . $dealerCardRank[0] . '点です。' . PHP_EOL;
      $rule = new Rule($this->usedCards);
    for ($i = 0; $i < $participants * 2; $i += 2) {
      ${$names[$i]} = new Rule($this->usedCards);

      if (!Judge::judgePlayerScoreEqual21($playerCardRank[$i])) {

        $playerCard[$i] = ${$names[$i]}->choiceRule($playerCard[$i], $names[$i]);
        $this->discardCards($playerCard[$i]);
        $betNum[$names[$i]] = ${$names[$i]}->bet;
        $playerCardRank[$i] = $player->getCardRank($playerCard[$i]);
        if(empty($playerCard[$i])){
            echo $names[$i].'さん降参するとはね、あんたは失格です。' . PHP_EOL;
            unset($playerCard[$i]);
            unset($playerCardRank[$i]);
            $losers[] = $names[$i];
          }elseif (is_array($playerCard[$i][0])) {
           $playerCard[$i+1] = $playerCard[$i][1];
              $playerCard[$i] = $playerCard[$i][0];
              $names[$i+1] = $names[$i];
            $playerCardRank[$i + 1] = $player->getCardRank($playerCard[$i]);
            if (Judge::getWinner($playerCardRank[$i])) {
              echo $names[$i+1] . 'さん、21をオーバーしましたね、あんたは失格です。' . PHP_EOL;
              unset($playerCard[$i + 1]);
              unset($playerCardRank[$i + 1]);
              $losers[] = $names[$i + 1];
            }
          }elseif (Judge::getWinner($playerCardRank[$i])){
          echo $names[$i].'さん、21をオーバーしましたね、あんたは失格です。' . PHP_EOL;
          unset($playerCard[$i]);
          unset($playerCardRank[$i]);
          $losers[] = $names[$i];
        }
    }
      }
 if (empty($playerCard)) {
          echo '私の一人勝ちです。'.PHP_EOL;
          echo 'ちなみに';
      }
    $dealerCardRank = (int)array_sum($dealerCardRank);
      echo '私が引いたカードの2枚目をお見せします。'. $dealerCards[1] . 'でした。合計は' . $dealerCardRank  . 'となりました。' . PHP_EOL;
      if(Judge::judgeDealerScoreUnder17($dealerCardRank)) {
      $dealerCards = $this->dealerPickAdditionalCard($dealerCards);
      $dealerCardRank = $player->getCardRank($dealerCards);
          }
         $result = Judge::finalGetWinner($playerCardRank, $dealerCardRank, $names);
         if (!empty($losers)) {
          foreach($losers as $loser) {
             $result['lose'][] =  $loser;
          }
         }
         if(!empty($result['win'])) {
          echo '勝者は' . implode('さん', $result['win']) . 'さんです。'.PHP_EOL;
          foreach ($result['win'] as $winner) {
            $this->resultMoney[$winner] += $betNum[$winner];
          }
         }
    if (!empty($result['draw'])) {
      echo implode('さん', $result['draw']).'さんは引き分けです。' . PHP_EOL;
      // foreach ($result['draw'] as $drawer) {
      //   $money[$drawer] += $betNum[$drawer];
      // }
    }
    if (!empty($result['lose'])) {
      echo implode('さん', $result['lose']) . 'さんは負けです。' . PHP_EOL;
      foreach ($result['lose'] as $loser) {
        $this->resultMoney[$loser] -= $betNum[$loser];
      }
    }
     if(empty($result['win']) && empty($result['draw'])) {
      echo '私の一人勝ちです。' . PHP_EOL;
         }
         foreach ($names as $name) {
          echo $name . 'さんのベット結果は' . $this->resultMoney[$name] . 'でした。' . PHP_EOL;
         }

         foreach ($this->resultMoney as $name => $money) {
          if ($money <= 0) {
            $this->rank[] = $name;
            unset($this->resultMoney[$name]);
          }
          if (empty($this->resultMoney)) {
            for ($i = 0; $i < count($this->rank); $i++) {
              $rank = count($this->rank);
                echo $rank . '位は' . $this->rank[$i] . 'さんです。' . PHP_EOL;
                $rank--;
            }
          }
         }
         STDIN;
         $this->usedCards = [];
      }

      public function selectPlayersNumber () {
        if (empty($this->resultMoney)) {
          echo 'こんにちは！ブラックジャックをしたいですか？' . PHP_EOL;
    echo '3人までプレイ可能です。何人でのプレイを希望ですか？' . PHP_EOL;
    while (true) {
      $participants = trim(fgets(STDIN));
      if (in_array($participants, [1, 2, 3])) {
        break;
      } else {
        echo '正しい数値・形式・書体で入力してください。' . PHP_EOL;
      }
    }
        } else {
          $participants = count($this->resultMoney);
        }

        return $participants;
      }

      public function getPlayerName($participants) {
          $names = [];
          for ($i = 0; $i < $participants * 2; $i += 2) {
            while(true) {
              echo 'お名前は？' . PHP_EOL;
            $name = trim(fgets(STDIN));
            if (!in_array($name, $names)) {
              $names[$i] = $name;
              break;
            }
            echo '重複していない名前を登録してください' . PHP_EOL;
            }

          }
          for ($i = 0; $i < $participants * 2; $i += 2) {
            echo $names[$i] . 'さん' . PHP_EOL;
          }
          return $names;
      }
      public function discardCards($cards) {

    foreach ($cards as $card) {

        if(!in_array($card, $this->usedCards)) {
        $this->usedCards[] = $card;
      }
    }
      }

      public function playerPickAdditionalCard($playerCard, $name) {
    $player = new Player($this->usedCards);
    $playerCardRank = $player->getCardRank($playerCard);
          echo $name.'さん、現在'. $playerCardRank.'点ですが追加でカードを引きますか？(Y/N) :' ; $answer = trim(fgets(STDIN)) ; PHP_EOL;
    if ($answer ==='Y' || $answer === 'y') {
      $playerCard[] = (string)$player->getAdditionalCards();
      $this->discardCards($playerCard);
      $playerCardRank = $player->getCardRank($playerCard);
      echo $name.'さんが引いたカードは' . $playerCard[count($playerCard)-1] . 'でした。現在のカードのスコアは' . $playerCardRank . '点です。' . PHP_EOL;
      if (Judge::judgeOver21($playerCardRank)){
        return $playerCard;
      }
     return $this->playerPickAdditionalCard($playerCard, $name);
    } elseif ($answer === 'N' || $answer === 'n') {
      return $playerCard;
    } else {
      echo 'YかNで答えなさい';
      return $this->playerPickAdditionalCard($playerCard, $name);
    }
    return $playerCard;

      }
  public function dealerPickAdditionalCard($dealerCards)
  {
    $player = new Player($this->usedCards);
    $dealerCardRank = $player->getCardRank($dealerCards);
    if (Judge::judgeDealerScoreUnder17($dealerCardRank)) {
      echo '追加でカードを引きます。'.PHP_EOL;
      $dealerCards[] = $player->getAdditionalCards();
      $this->discardCards($dealerCards);
      $dealerCardRank = $player->getCardRank($dealerCards);
      echo '引いたカードは' . $dealerCards[count($dealerCards) - 1] . 'でした。現在のカードのスコアは' . $dealerCardRank . '点です。' . PHP_EOL;
      if (Judge::judgeOver21($dealerCardRank)) {
        return $dealerCards;
      }
      return $this->dealerPickAdditionalCard($dealerCards);
    } else {
      return $dealerCards;
    }
    return $dealerCards;

  }
    }
$game = new BJGame();
$money = $game->gameStart();
while(true) {
  $money = $game->gameStart($money);
}
