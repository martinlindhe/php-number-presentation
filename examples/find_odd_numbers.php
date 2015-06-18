<?php

require_once __DIR__.'/../vendor/autoload.php';

use MartinLindhe\NumberPresentation\NumberBase;

function contains_others($s, $expected = [])
{
    $found = count_chars($s, 1);

    foreach ($found as $ascii => $cnt) {
        $c = chr($ascii);
        if (!in_array($c, $expected)) {
            return true;
        }
    }

    return false;
}

// finds 86000, which only uses 0 and 1 for base 2,3,4,5

for ($i = 1; $i < 900000; $i++) {

    $fail = false;
    for ($base = 2; $base < 6; $base++) {
        $x = (new NumberBase($i))->to($base)->__toString();
        if (contains_others($x, ['0', '1'])) {
            $fail = true;
            break;
        }
    }
    if (!$fail) {
        d('i = '.$i);
    }
}
