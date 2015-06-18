<?php

use MartinLindhe\NumberPresentation\NumberBase;

class NumberBaseTest extends \PHPUnit_Framework_TestCase
{
    public function testBase()
    {
        $this->assertEquals(NumberBase::BINARY, (new NumberBase(1, NumberBase::BINARY))->getUnit());
    }

    /**
     * @expectedException Exception
     */
    public function testBadInput()
    {
        $x = new NumberBase('not a number');
    }

    public function testNumberBaseFromString()
    {
        $this->assertSame(NumberBase::BINARY, NumberBase::getNumberBaseFromString('b0100'));
        $this->assertSame(NumberBase::DECIMAL, NumberBase::getNumberBaseFromString('123'));
        $this->assertSame(NumberBase::OCTAL, NumberBase::getNumberBaseFromString('o555'));

        $this->assertSame(NumberBase::HEXADECIMAL, NumberBase::getNumberBaseFromString('0x44'));
        $this->assertSame(NumberBase::HEXADECIMAL, NumberBase::getNumberBaseFromString('fe2a'));
    }

    public function testParseValue()
    {
        $this->assertSame(NumberBase::HEXADECIMAL, (new NumberBase('0x44'))->getUnit());
        $this->assertSame(NumberBase::HEXADECIMAL, (new NumberBase('fefe'))->getUnit());

        $this->assertSame(NumberBase::BINARY, (new NumberBase('b0100'))->getUnit());
        $this->assertSame(NumberBase::OCTAL, (new NumberBase('o44'))->getUnit());
        $this->assertSame(NumberBase::DECIMAL, (new NumberBase('44'))->getUnit());
    }

    public function testConvertTo()
    {
        $this->assertSame('4', (new NumberBase('b0100'))->to(NumberBase::DECIMAL)->__toString());
        $this->assertSame('4', (new NumberBase('b0100'))->to(NumberBase::HEXADECIMAL)->__toString());
        $this->assertSame('4', (new NumberBase('b0100'))->to(NumberBase::OCTAL)->__toString());
        $this->assertSame('100', (new NumberBase('b0100'))->to(NumberBase::BINARY)->__toString());
    }

    public function test82000()
    {
        // NOTE: 82000 is special in that it is only using 0 and 1 in base 2,3,4 and 5

        $this->assertSame('10100000001010000', (new NumberBase(82000))->to(2)->__toString());
        $this->assertSame('11011111001', (new NumberBase(82000))->to(3)->__toString());
        $this->assertSame('110001100', (new NumberBase(82000))->to(4)->__toString());
        $this->assertSame('10111000', (new NumberBase(82000))->to(5)->__toString());
        $this->assertSame('1431344', (new NumberBase(82000))->to(6)->__toString());
    }
}
