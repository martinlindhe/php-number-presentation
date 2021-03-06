<?php

use MartinLindhe\NumberPresentation\MathSymbol;

class MathSymbolTest extends \PHPUnit_Framework_TestCase
{
    public function testSuperScript()
    {
        $this->assertSame('¹', MathSymbol::superScriptNumber(1));
        $this->assertSame('¹²³', MathSymbol::superScriptNumber(123));
        $this->assertSame('⁻¹', MathSymbol::superScriptNumber(-1));
        $this->assertSame('⁻¹⁵', MathSymbol::superScriptNumber(-15));
    }

    public function testFromSuperScript()
    {
        $this->assertSame(1, MathSymbol::fromSuperScriptNumber('¹'));
        $this->assertSame(123, MathSymbol::fromSuperScriptNumber('¹²³'));
        $this->assertSame(-1, MathSymbol::fromSuperScriptNumber('⁻¹'));
        $this->assertSame(-15, MathSymbol::fromSuperScriptNumber('⁻¹⁵'));
    }

    public function testSubScript()
    {
        $this->assertSame('₁', MathSymbol::subScriptNumber(1));
        $this->assertSame('₁₂₃', MathSymbol::subScriptNumber(123));
        $this->assertSame('₋₁', MathSymbol::subScriptNumber(-1));
        $this->assertSame('₋₁₅', MathSymbol::subScriptNumber(-15));
    }

    public function testFromSubScript()
    {
        $this->assertSame(1, MathSymbol::fromSubScriptNumber('₁'));
        $this->assertSame(123, MathSymbol::fromSubScriptNumber('₁₂₃'));
        $this->assertSame(-1, MathSymbol::fromSubScriptNumber('₋₁'));
        $this->assertSame(-15, MathSymbol::fromSubScriptNumber('₋₁₅'));
    }

    public function testIsSuperScript()
    {
        $this->assertSame(true, MathSymbol::isSuperScript('¹'));
        $this->assertSame(false, MathSymbol::isSuperScript('1'));
        $this->assertSame(false, MathSymbol::isSuperScript('₁'));
    }

    public function testIsSubScript()
    {
        $this->assertSame(true, MathSymbol::isSubScript('₁'));
        $this->assertSame(false, MathSymbol::isSubScript('1'));
        $this->assertSame(false, MathSymbol::isSubScript('¹'));
    }
}
