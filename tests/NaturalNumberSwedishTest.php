<?php

use MartinLindhe\NumberPresentation\NaturalNumberSwedish;

class NaturalNumberSwedishTest extends \PHPUnit_Framework_TestCase
{
    public function testParseWholeNumbers()
    {
        $this->assertSame('0', NaturalNumberSwedish::parse('noll'));
        $this->assertSame('1', NaturalNumberSwedish::parse('en'));
        $this->assertSame('1', NaturalNumberSwedish::parse('ett'));
        $this->assertSame('5', NaturalNumberSwedish::parse('fem'));
        $this->assertSame('5', NaturalNumberSwedish::parse(5));
        $this->assertSame('15', NaturalNumberSwedish::parse('femton'));
        $this->assertSame('52', NaturalNumberSwedish::parse('femtiotvå'));
        $this->assertSame('99', NaturalNumberSwedish::parse('nittionio'));

        $this->assertSame('199', NaturalNumberSwedish::parse('hundranittionio'));
        $this->assertSame('199', NaturalNumberSwedish::parse('etthundranittionio'));
        $this->assertSame('200', NaturalNumberSwedish::parse('två hundra'));
        $this->assertSame('200', NaturalNumberSwedish::parse('2 hundra'));
        $this->assertSame('666', NaturalNumberSwedish::parse('sexhundrasextiosex'));
        $this->assertSame('999', NaturalNumberSwedish::parse('niohundranittionio'));

        $this->assertSame('1000', NaturalNumberSwedish::parse('tusen'));
        $this->assertSame('1000', NaturalNumberSwedish::parse('ettusen'));

        $this->assertSame('2000', NaturalNumberSwedish::parse('2 tusen'));
        $this->assertSame('2000', NaturalNumberSwedish::parse('tvåtusen'));
        $this->assertSame('2011', NaturalNumberSwedish::parse('tvåtusenelva'));
        $this->assertSame('2109', NaturalNumberSwedish::parse('tvåtusenetthundranio'));
        $this->assertSame('2199', NaturalNumberSwedish::parse('tvåtusenetthundranittionio'));

        $this->assertSame('9000', NaturalNumberSwedish::parse('niotusen'));
        $this->assertSame('9000', NaturalNumberSwedish::parse('nio tusen'));

        $this->assertSame('9999', NaturalNumberSwedish::parse('9999'));
        $this->assertSame('9999', NaturalNumberSwedish::parse(9999));

        $this->assertSame('89000', NaturalNumberSwedish::parse('åttioniotusen'));
        $this->assertSame('99998', NaturalNumberSwedish::parse('nittioniotusenniohundranittioåtta'));

        $this->assertSame('100000', NaturalNumberSwedish::parse('hundratusen'));
        $this->assertSame('100000', NaturalNumberSwedish::parse('hundra tusen'));
        $this->assertSame('100000', NaturalNumberSwedish::parse('etthundratusen'));
        $this->assertSame('100000', NaturalNumberSwedish::parse('etthundra tusen'));
        $this->assertSame('100000', NaturalNumberSwedish::parse('100 tusen'));

        $this->assertSame('203019', NaturalNumberSwedish::parse('tvåhundratusentretusennitton'));

        $this->assertSame('1234567', NaturalNumberSwedish::parse('enmiljontvåhundratrettiofyratusenfemhundrasextiosju'));
        $this->assertSame('8000000', NaturalNumberSwedish::parse('åtta miljoner'));
        $this->assertSame('8000000', NaturalNumberSwedish::parse('8 miljoner'));

        $this->assertSame('111111111', NaturalNumberSwedish::parse('etthundraelvamiljoneretthundraelvatusenetthundraelva'));
        $this->assertSame('999999999', NaturalNumberSwedish::parse('niohundranittioniomiljonerniohundranittioniotusenniohundranittionio'));

        $this->assertSame('8000000000', NaturalNumberSwedish::parse('åttamiljarder'));
        $this->assertSame('8000000000', NaturalNumberSwedish::parse('åtta miljarder'));
        $this->assertSame('8000000000', NaturalNumberSwedish::parse('8 miljarder'));

        $this->assertSame('8000000000000', NaturalNumberSwedish::parse('åttabiljoner'));
        $this->assertSame('8000000000000', NaturalNumberSwedish::parse('åtta biljoner'));
        $this->assertSame('8000000000000', NaturalNumberSwedish::parse('8 biljoner'));

        $this->assertSame('1000000000000000', NaturalNumberSwedish::parse('1 biljard'));
        $this->assertSame('8000000000000000', NaturalNumberSwedish::parse('åtta biljarder'));
    }

    public function testParseWithDecimals()
    {
        $this->assertSame('199.2', NaturalNumberSwedish::parse('hundranittionio komma två'));

        $this->assertSame('199.64', NaturalNumberSwedish::parse('hundranittionio komma sextiofyra'));

        $this->assertSame('199.319', NaturalNumberSwedish::parse('hundranittionio komma trehundranitton'));
    }

    public function testParseFractions()
    {
        $this->assertSame('0.5', NaturalNumberSwedish::parse('hälften'));
        $this->assertSame('0.2', NaturalNumberSwedish::parse('en femtedel'));

        $this->assertSame('0.75', NaturalNumberSwedish::parse('tre fjärdedelar'));
        $this->assertSame('0.6', NaturalNumberSwedish::parse('tre femtedelar'));

        $this->assertSame('15.6', NaturalNumberSwedish::parse('femton och tre femtedelar'));
    }

    public function testParseScientificNotation()
    {
        $this->assertSame('40000000', NaturalNumberSwedish::parse('400 * 10^5'));
        $this->assertSame('40000000', NaturalNumberSwedish::parse('400 * 10⁵'));

        $this->assertSame('40010000', NaturalNumberSwedish::parse('400.1 * 10⁵'));
    }

    public function testPresent()
    {
        $this->assertSame('fem', NaturalNumberSwedish::present(5));
        $this->assertSame('tjugotre', NaturalNumberSwedish::present(23));
        $this->assertSame('nittionio', NaturalNumberSwedish::present(99));
        $this->assertSame('etthundrasjuttiosex', NaturalNumberSwedish::present(176));
        $this->assertSame('tvåhundra', NaturalNumberSwedish::present(200));
        $this->assertSame('ettusen', NaturalNumberSwedish::present(1000));
        $this->assertSame('ettusenetthundra', NaturalNumberSwedish::present(1100));
        $this->assertSame('ettusenniohundratjugo', NaturalNumberSwedish::present(1920));
        $this->assertSame('tvåtusen', NaturalNumberSwedish::present(2000));
        $this->assertSame('tretusen', NaturalNumberSwedish::present(3000));
        $this->assertSame('nittontusenåttahundrasextio', NaturalNumberSwedish::present(19860));
        $this->assertSame('åttioniotusen', NaturalNumberSwedish::present(89000));
        $this->assertSame('femhundraåttioniotusen', NaturalNumberSwedish::present(589000));
        $this->assertSame('enmiljontvåhundratrettiofyratusenfemhundrasextiosju', NaturalNumberSwedish::present(1234567));
        $this->assertSame('niomiljoneråttahundrasjuttiosextusenfemhundrafyrtiotre', NaturalNumberSwedish::present(9876543));
        $this->assertSame('tolvmiljoner', NaturalNumberSwedish::present(12000000));
        $this->assertSame('tolvmiljonersexhundratusen', NaturalNumberSwedish::present(12600000));
        $this->assertSame('tvåhundrasextiomiljoner', NaturalNumberSwedish::present(260000000));
        $this->assertSame('etthundratjugotremiljonerfyrahundrafemtiosextusensjuhundraåttionio', NaturalNumberSwedish::present(123456789));
        $this->assertSame('niohundraåttiosjumiljonersexhundrafemtiofyratusentrehundratjugoett', NaturalNumberSwedish::present(987654321));
        $this->assertSame('etthundraelvamiljoneretthundraelvatusenetthundraelva', NaturalNumberSwedish::present(111111111));
        $this->assertSame('niohundranittioniomiljonerniohundranittioniotusenniohundranittionio', NaturalNumberSwedish::present(999999999));

        $this->assertSame('åttamiljarder', NaturalNumberSwedish::present(8000000000));
        $this->assertSame('åttahundramiljarder', NaturalNumberSwedish::present(800000000000));
    }
}
