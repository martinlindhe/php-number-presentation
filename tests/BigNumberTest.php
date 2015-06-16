<?php

use MartinLindhe\NumberPresentation\BigNumber;

class BigNumberTest extends \PHPUnit_Framework_TestCase
{

    public function testStripTrailingZeroes()
    {
        $this->assertSame('5', BigNumber::stripTrailingZeroes('5.0'));
        $this->assertSame('5', BigNumber::stripTrailingZeroes('5'));
        $this->assertSame('5.1', BigNumber::stripTrailingZeroes('5.1'));
    }

    public function testAdd()
    {
        $this->assertSame('42', (new BigNumber('32'))->add(new BigNumber('10'))->__toString());
        $this->assertSame('42', (new BigNumber(32))->add(new BigNumber(10))->__toString());
        $this->assertSame('42', (new BigNumber(32.0))->add(new BigNumber(10.0))->__toString());
        $this->assertSame('42.1', (new BigNumber('32.10'))->add(new BigNumber('10.000'))->__toString());

        $this->assertSame('42', (new BigNumber('32'))->add('10')->__toString());
        $this->assertSame('42.1', (new BigNumber('32.10'))->add('10.000')->__toString());
    }

    public function testSub()
    {
        $this->assertSame('22', (new BigNumber('32'))->sub('10')->__toString());
    }

    public function testMul()
    {
        $this->assertSame('90.3', (new BigNumber('30.1'))->mul('3')->__toString());
    }

    public function testDiv()
    {
        $this->assertSame('10.1', (new BigNumber('30.3'))->div('3')->__toString());
    }

    public function testPow()
    {
        $this->assertSame('125', (new BigNumber('5'))->pow('3')->__toString());
    }

    public function testSqrt()
    {
        $this->assertSame('4', (new BigNumber('16'))->sqrt()->__toString());
    }
}
