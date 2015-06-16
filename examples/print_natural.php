<?php
// prints random numbers in natural language

require_once __DIR__.'/../vendor/autoload.php';

use MartinLindhe\NumberPresentation\NaturalNumberSwedish;

do {
    $val = mt_rand(0, 999999999);
    echo NaturalNumberSwedish::present($val)."\n";
} while (1);
