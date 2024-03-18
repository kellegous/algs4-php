<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

/******************************************************************************
 *  Execution:    php bin/knuth.php < list.txt
 *  Data files:   https://algs4.cs.princeton.edu/11model/cards.txt
 *                https://algs4.cs.princeton.edu/11model/cardsUnicode.txt
 *
 *  Reads in a list of strings and prints them in random order.
 *  The Knuth (or Fisher-Yates) shuffling algorithm guarantees
 *  to rearrange the elements in uniformly random order, under
 *  the assumption that Math.random() generates independent and
 *  uniformly distributed numbers between 0 and 1.
 *
 *  % more data/11model/cards.txt
 *  2C 3C 4C 5C 6C 7C 8C 9C 10C JC QC KC AC
 *  2D 3D 4D 5D 6D 7D 8D 9D 10D JD QD KD AD
 *  2H 3H 4H 5H 6H 7H 8H 9H 10H JH QH KH AH
 *  2S 3S 4S 5S 6S 7S 8S 9S 10S JS QS KS AS
 *
 *  % php bin/knuth.php < data/11model/cards.txt
 *  6H
 *  9C
 *  8H
 *  7C
 *  JS
 *  ...
 *  KH
 *
 *  % more data/11model/cardsUnicode.txt
 *  2♣ 3♣ 4♣ 5♣ 6♣ 7♣ 8♣ 9♣ 10♣ J♣ Q♣ K♣ A♣
 *  2♦ 3♦ 4♦ 5♦ 6♦ 7♦ 8♦ 9♦ 10♦ J♦ Q♦ K♦ A♦
 *  2♥ 3♥ 4♥ 5♥ 6♥ 7♥ 8♥ 9♥ 10♥ J♥ Q♥ K♥ A♥
 *  2♠ 3♠ 4♠ 5♠ 6♠ 7♠ 8♠ 9♠ 10♠ J♠ Q♠ K♠ A♠
 *
 *  % php knuth.php < cardsUnicode.txt
 *  2♠
 *  K♥
 *  6♥
 *  5♣
 *  J♣
 *  ...
 *  A♦
 *
 ******************************************************************************/

use Kellegous\Algs4\Knuth;
use Kellegous\Algs4\Stdio;

$a = iterator_to_array(Stdio::in()->readStrings());
foreach (Knuth::shuffle($a) as $s) {
    printf("%s\n", $s);
}