<?php

require_once __DIR__.'/../vendor/autoload.php';

use MartinLindhe\NumberPresentation\NaturalNumberSwedish;

$val = NaturalNumberSwedish::parse('400 * 10^5');

d($val);
