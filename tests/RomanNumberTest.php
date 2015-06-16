<?php

use MartinLindhe\NumberPresentation\RomanNumber;

class RomanNumberTest extends \PHPUnit_Framework_TestCase
{
    public function testIsValid()
    {
        $this->assertSame(true, RomanNumber::isValid('XII'));
        $this->assertSame(false, RomanNumber::isValid('MMC1'));
    }

    public function testConvert()
    {
        $this->assertSame(1988, (new RomanNumber)->parse('MCMLXXXVIII')->getAsInteger());
        $this->assertSame(2010, (new RomanNumber('MMX'))->getAsInteger());
        $this->assertSame(1999, (new RomanNumber('MCMXCIX'))->getAsInteger());
        $this->assertSame(4999, (new RomanNumber('MMMMCMXCIX'))->getAsInteger());

        $this->assertSame('XIV', (new RomanNumber('XIV'))->getAsRoman());
        $this->assertSame('MCMLXXXVIII', (new RomanNumber(1988))->getAsRoman());
    }

    /**
     * @expectedException Exception
     */
    public function testInvalid()
    {
        $x = (new RomanNumber)->parse('not a number')->getAsRoman();
    }

    /**
     * @expectedException Exception
     */
    public function testTooBigNumber()
    {
        $x = (new RomanNumber)->parse('MMMMMM')->getAsRoman();
    }
}
