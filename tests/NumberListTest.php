<?php

use MartinLindhe\NumberPresentation\NumberList;

class NumberListTest extends \PHPUnit_Framework_TestCase
{
    public function testOne()
    {
        $this->assertSame(['1', '2', '3'], NumberList::parseNumbersToArray('1, 2 och 3'));
        $this->assertSame(['1', '2', '3'], NumberList::parseNumbersToArray('1, 2, 3'));

        $this->assertSame(['1', '2', '3'], NumberList::parseNumbersToArray('ett, två, tre'));
        $this->assertSame(['1', '2', '3'], NumberList::parseNumbersToArray('ett, två och tre'));
    }
}
