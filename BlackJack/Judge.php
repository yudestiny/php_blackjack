<?php

namespace BlackJack;

class Judge
{
  public static function judgePlayerScoreEqual21 ($cardRank):bool {
    if ($cardRank === 21) {
      return true;
    }else {
      return false;
    }
  }

public static function judgeDealerScoreUnder17 ($cardRank):bool {
      if ($cardRank < 17) {
        return true;
      } else {
        return false;
      }
  }
  public static function judgeOver21(int $cardRank):bool
  {
    if ($cardRank >= 21) {
      return true;
    } else {
      return false;
    }
  }
  public static function getWinner(int $playerCardRank):bool {

      if ($playerCardRank > 21) {
              return true;
            } else {
              return false;
            }
  }
  public static function finalGetWinner($playerCardRanks, $dealerCardRank, $names)
  {
    $result = [
      'win' => [],
      'lose' => [],
      'draw' => [],
    ];
    foreach ($playerCardRanks as $num => $playerCardRank) {
      if ($dealerCardRank > 21) {
        $result['win'][] = $names[$num];
      } elseif ($playerCardRank > $dealerCardRank) {
        $result['win'][] = $names[$num];
      } elseif ($playerCardRank < $dealerCardRank) {
        $result['lose'][] = $names[$num];
      } elseif ($playerCardRank == $dealerCardRank) {
        $result['draw'][] = $names[$num];
    }

    }
return $result;


  }

}
