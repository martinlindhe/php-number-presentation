<?php

use MartinLindhe\NumberPresentation\RationalNumber;

class RationalNumberTest extends \PHPUnit_Framework_TestCase
{
    public function testOneThird()
    {
        $n = new RationalNumber(1 / 3);
        $this->assertSame(0.3333333333333333, $n->asDouble());
        $this->assertSame('1/3', $n->asRational());
    }

    public function testOneFourth()
    {
        $n = new RationalNumber(0.25);
        $this->assertSame(0.25, $n->asDouble());
        $this->assertSame('1/4', $n->asRational());
    }

    public function testOneFifth()
    {
        $n = new RationalNumber(0.2);
        $this->assertSame(0.2, $n->asDouble());
        $this->assertSame('1/5', $n->asRational());
    }

    public function testWholeNumber()
    {
        $this->assertSame('35', (new RationalNumber(35))->asRational());
    }

    public function testThreeFourths()
    {
        $this->assertSame('3/4', (new RationalNumber(0.75))->asRational());
    }

    public function testMore()
    {
        $this->assertSame('67/74', (new RationalNumber(0.9054054054))->asRational());

        $this->assertSame('14/27', (new RationalNumber(0.5185185185))->asRational());

        $this->assertSame('1/7', (new RationalNumber(0.1428571428))->asRational());

        $this->assertSame('35001/1000', (new RationalNumber(35.001))->asRational());

        $this->assertSame('1/999999', (new RationalNumber(0.000001000001))->asRational());

        $this->assertSame('200/3', (new RationalNumber(66.66667))->asRational());

        $this->assertSame('1393/985', (new RationalNumber(sqrt(2)))->asRational());

        $this->assertSame('748/1731', (new RationalNumber(0.43212))->asRational());
    }

    public function testNew()
    {
        $tmp = new RationalNumber(5);
        $this->assertSame(5, $tmp->asDouble());
        $this->assertSame('5', $tmp->asRational());
    }

    public function testParse()
    {
        $tmp = new RationalNumber('5/1');
        $this->assertSame(5, $tmp->asDouble());
        $this->assertSame('5', $tmp->asRational());

        $tmp = new RationalNumber('2/2');
        $this->assertSame(1, $tmp->asDouble());
        $this->assertSame('1', $tmp->asRational());

        $tmp = new RationalNumber('1/3');
        $this->assertSame(0.3333333333333333, $tmp->asDouble());
        $this->assertSame('1/3', $tmp->asRational());
    }
}
