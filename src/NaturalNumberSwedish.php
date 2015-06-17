<?php namespace MartinLindhe\NumberPresentation;

class NaturalNumberSwedish
{
    static $upToTwenty = [
        0 => "", 1 => "ett|en", "två", "tre", "fyra", "fem", "sex", "sju", "åtta", "nio",
        "tio", "elva", "tolv", "tretton", "fjorton", "femton", "sexton", "sjutton", "arton", "nitton"
    ];

    static $tens = [
        0 => "", 1 => "tio", "tjugo", "trettio", "fyrtio", "femtio", "sextio", "sjuttio", "åttio", "nittio"
    ];

    /**
     * @param string $s
     * @return string|null bcmath number
     */
    private static function parseScientificNotation($s)
    {
        $parts = explode('*', $s);
        $num = trim($parts[0]);

        // 10 to the power of, either in "10^2" syntax, or utf8 string "10²"
        $base = 10;

        $pos = strpos($parts[1], (string) $base);
        if ($pos === false) {
            return null;
        }

        $rest = substr($parts[1], $pos + strlen((string) $base));
        if ($rest{0} == '^') {
            $power = (int) substr($rest, 1);
        } else {
            $power = MathSymbol::fromSuperScriptNumber($rest);
            if ($power === null) {
                return null;
            }
        }

        $pow = bcpow($base, $power);
        $res = bcmul($num, $pow);
        return $res;
    }

    /**
     * @param string $s
     * @return string|null bcmath number
     * @throws \Exception
     */
    public static function parse($s)
    {
        //bcscale(2);

        $res = self::parsePrivate($s);

        if ($res === null) {
            return null;
        }

        return BigNumber::stripTrailingZeroes($res);
    }

    private static function parsePrivate($s)
    {
        if (is_numeric($s)) {
            return ''.$s;
        }

        preg_match('/^(?<num>[\d\.]+) \* (?<mult>[\d\w^.]+)$/ui', $s, $match);
        if (isset($match['num']) && isset($match['mult'])) {
            return self::parseScientificNotation($s);
        }

        preg_match('/^(?<num>[\d]+) (?<size>hundra|tusen|miljon(er)?|miljard(er)?|biljon(er)?|biljard(er)?)+$/ui', $s, $match);
        if (isset($match['num']) && isset($match['size'])) {
            if ($res = self::mapToMultiplier($match['num'], $match['size'])) {
                return $res;
            }
        }

        // https://sv.wikipedia.org/wiki/Namn_p%C3%A5_stora_tal
        $s = str_replace(' hundra', 'hundra', $s);
        $s = str_replace(' tusen', 'tusen', $s);
        $s = str_replace(' miljon', 'miljon', $s);          // 10^6
        $s = str_replace(' miljard', 'miljard', $s);        // 10^9
        $s = str_replace(' biljon', 'biljon', $s);          // 10^12
        $s = str_replace(' biljard', 'biljard', $s);        // 10^15

        // "fem komma två"
        $comma = ' komma ';
        $pos = mb_strpos($s, $comma);
        if ($pos !== false) {
            $integer = mb_substr($s, 0, $pos);

            $int = self::parse($integer);

            $decimals = mb_substr($s, $pos + mb_strlen($comma));
            $dec = self::parse($decimals);

            $scale = bcpow(10, strlen($dec));

            return (new BigNumber($dec))
                ->div($scale)
                ->add($int)
                ->__toString();
        }

        // "femton och tre fjärdedelar" = 15.75
        preg_match('/^(?<num>[\d\w ]+) och (?<frac>[\d\w ]+)$/ui', $s, $match);
        if (isset($match['num']) && isset($match['frac'])) {
            $num = self::parse($match['num']);
            $frac = self::parseFractions($match['frac']);
            return (new BigNumber($num))->add($frac)->__toString();
        }

        if ($s == 'noll') {
            return '0';
        }

        // 1,000,000,000,000 - 999,999,999,999,999 (1 biljard)
        if (strpos($s, 'biljard') !== false) {
            for ($i = 1; $i <= 999; $i++) {
                $prefix = $i == 1 ? 'enbiljard' : self::present($i) . 'biljard';
                if (substr($s, 0, strlen($prefix)) == $prefix) {
                    $mul = bcmul($i, 1000000000000000);
                    $res = bcadd($mul, self::parse(substr($s, strlen($prefix))));
                    return $res;
                }
            }
        }

        // 1,000,000,000,000 - 999,999,999,999,999 (1 biljon)
        if (strpos($s, 'biljon') !== false) {
            for ($i = 1; $i <= 999; $i++) {
                $prefix = $i == 1 ? 'enbiljon' : self::present($i) . 'biljoner';
                if (substr($s, 0, strlen($prefix)) == $prefix) {
                    $mul = bcmul($i, 1000000000000);
                    $res = bcadd($mul, self::parse(substr($s, strlen($prefix))));
                    return $res;
                }
            }
        }

        // 1,000,000,000 - 999,999,999,999 (1 miljard)
        if (strpos($s, 'miljard') !== false) {
            for ($i = 1; $i <= 999; $i++) {
                // NOTE: På amerikansk engelska – och sedan 1974 även på brittisk engelska
                //       heter miljard "billion". Dessförinnan var en miljard en "milliard"
                //       och en biljon en "billion" i Storbritannien.
                $prefix = $i == 1 ? 'enmiljard' : self::present($i) . 'miljarder';
                if (substr($s, 0, strlen($prefix)) == $prefix) {
                    $mul = bcmul($i, 1000000000);
                    $res = bcadd($mul, self::parse(substr($s, strlen($prefix))));
                    return $res;
                }
            }
        }

        // 1,000,000 - 999,999,999 (1 miljon)
        if (strpos($s, 'miljon') !== false) {
            for ($i = 1; $i <= 999; $i++) {
                $prefix = $i == 1 ? 'enmiljon' : self::present($i) . 'miljoner';
                if (substr($s, 0, strlen($prefix)) == $prefix) {
                    $mul = bcmul($i, 1000000);
                    $res = bcadd($mul, self::parse(substr($s, strlen($prefix))));
                    return $res;
                }
            }
        }

        // 1,000 - 999,999
        for ($i = 1; $i <= 999; $i++) {
            $prefix = self::present($i) . 'tusen';
            if ($i == 1 && substr($s, 0, 11) == 'hundratusen') {
                $mul = bcmul($i, 100000);
                $res = bcadd($mul, self::parse(substr($s, 11)));
                return $res;
            }
            if ($i == 1 && substr($s, 0, 14) == 'etthundratusen') {
                $mul = bcmul($i, 100000);
                $res = bcadd($mul, self::parse(substr($s, 14)));
                return $res;
            }
            if ($i == 1 && substr($s, 0, 5) == 'tusen') {
                $mul = bcmul($i, 1000);
                $res = bcadd($mul, self::parse(substr($s, 5)));
                return $res;
            }
            if ($i == 1 && substr($s, 0, 7) == 'ettusen') {
                $mul = bcmul($i, 1000);
                $res = bcadd($mul, self::parse(substr($s, 7)));
                return $res;
            }
            if (substr($s, 0, strlen($prefix)) == $prefix) {
                $mul = bcmul($i, 1000);
                $res = bcadd($mul, self::parse(substr($s, strlen($prefix))));
                return $res;
            }
        }

        // 100 - 999
        for ($i = 1; $i <= 9; $i++) {
            foreach (explode('|', self::$upToTwenty[$i]) as $prefix) {
                $prefix = $prefix.'hundra';
                if ($i == 1 && substr($s, 0, 6) == 'hundra') {
                    $mul = bcmul($i, 100);
                    $res = bcadd($mul, self::parse(substr($s, 6)));
                    return $res;
                }
                if (substr($s, 0, strlen($prefix)) == $prefix) {
                    $mul = bcmul($i, 100);
                    $res = bcadd($mul, self::parse(substr($s, strlen($prefix))));
                    return $res;
                }
            }
        }

        // 20 - 100
        foreach (self::$tens as $prefix) {
            if ($prefix && substr($s, 0, strlen($prefix)) == $prefix) {
                $tens = array_search(substr($s, 0, strlen($prefix)), self::$tens);
                $mul = bcmul($tens, 10);
                $res = bcadd($mul, self::parse(substr($s, strlen($prefix))));
                return $res;
            }
        }

        // 1 - 20, expand multiple values separated by |
        foreach (self::$upToTwenty as $num => $w) {
            foreach (explode('|', $w) as $p) {
                if ($p === $s) {
                    return ''.$num;
                }
            }
        }

        $res = self::parseFractions($s);
        if ($res !== null) {
            return $res;
        }

        err('FIXME unrecognized number: '.$s);
        return null;
    }

    private static function parseFractions($s)
    {
        if ($s == 'hälften') {
            return '0.5';
        }

        $x = explode(' ', $s, 2);

        if (count($x) == 2) {
            $num = self::parse($x[0]);
            $fraction = self::parseFraction($x[1]);

            if (!$fraction) {
                err('cant parse fraction ' . $x[1]);
                return null;
            }

            return (new BigNumber($num))
                ->mul($fraction)
                ->__toString();
        }

        return $s;
    }

    private static function parseFraction($s)
    {
        // tredjedels => tredjedel, tredjedelars => tredjedelar
        if (mb_substr($s, -1) == 's') {
            $s = mb_substr($s, 0, -1);
        }

        // tredjedelar => tredjedel
        if (mb_substr($s, -2) == 'ar') {
            $s = mb_substr($s, 0, -2);
        }

        $scales = [
            'halv'        => 1/2,
            'tredjedel'   => 1/3,
            'fjärdedel'   => 1/4,
            'femtedel'    => 1/5,
            'sjättedel'   => 1/6,
            'sjundedel'   => 1/7,
            'åttondedel'  => 1/8,     'åttondel' => 1/8,
            'niondedel'   => 1/9,      'niondel' => 1/9,
            'tiondedel'   => 1/10,     'tiondel' => 1/10,    'tiodel' => 1/10,
            'elftedel'    => 1/11,
            'tolftedel'   => 1/12,
            'trettondedel' => 1/13,  'trettondel' => 1/13,
            'tjugondedel' => 1/20,   'tjugondel' => 1/20,  'tjugodel' => 1/20,
            'hundradedel' => 1/100,  'hundradel' => 1/100,
        ];

        if (array_key_exists($s, $scales)) {
            return $scales[$s];
        }

        return null;
    }

    /**
     * @param int $n
     * @return string representation in swedish of input number (51 = "femtioett")
     * @throws \Exception
     */
    public static function present($n)
    {
        // 0 - 19
        if ($n < 20) {
            $x = explode('|', self::$upToTwenty[$n]);
            return $x[0];
        }

        // 20 - 99
        if ($n < 100) {
            $tiotal = intval(substr($n, -2, 1));
            $ental = intval(substr($n, -1, 1));

            return self::$tens[$tiotal] . self::present($ental);
        }

        // 100 - 999
        if ($n < 1000) {
            $hundratal = intval(substr($n, -3, 1));
            $last2 = intval(substr($n, -2));

            return self::present($hundratal) . "hundra" . self::present($last2);
        }

        // 1,000 - 99,999
        if ($n < 100000) {

            if ($n < 10000) {
                $tusental = intval(substr($n, -5, 1));
            } else {
                $tusental = intval(substr($n, -5, 2));
            }

            $last3 = intval(substr($n, -3));

            // NOTE correct spelling (avoids triple t)
            if ($tusental == 1) {
                return "ettusen" . self::present($last3);
            }

            return self::present($tusental) . "tusen" . self::present($last3);
        }

        // 100,000 - 999,999
        if ($n < 1000000) {
            $first3 = intval(substr($n, 0, 3));
            $last3 = intval(substr($n, 3));

            return self::present($first3) . "tusen" . self::present($last3);
        }

        // 1,000,000 -> 99,999,999 (miljoner)
        if ($n < 100000000) {

            if ($n < 10000000) {
                $millions = intval(substr($n, 0, 1));
                $rest = intval(substr($n, 1));
            } else {
                $millions = intval(substr($n, 0, 2));
                $rest = intval(substr($n, 2));
            }

            if ($millions == 1) {
                return "enmiljon" . self::present($rest);
            }

            return self::present($millions) . "miljoner" . self::present($rest);
        }

        // 100,000,000 -> 999,999,999 (miljoner)
        if ($n < 1000000000) {
            $millions = intval(substr($n, 0, 3));
            $rest = intval(substr($n, 3));

            return self::present($millions) . "miljoner" . self::present($rest);
        }

        // 1,000,000,000 -> 99,999,999,999 (miljarder)
        if ($n < 100000000000) {

            if ($n < 10000000000) {
                $millions = intval(substr($n, 0, 1));
                $rest = intval(substr($n, 1));
            } else {
                $millions = intval(substr($n, 0, 2));
                $rest = intval(substr($n, 2));
            }

            if ($millions == 1) {
                return "enmiljard" . self::present($rest);
            }

            return self::present($millions) . "miljarder" . self::present($rest);
        }

        // 100,000,000,000 -> 999,999,999,999 (miljarder)
        if ($n < 1000000000000) {
            $first = intval(substr($n, 0, 3));
            $rest = intval(substr($n, 3));

            return self::present($first) . "miljarder" . self::present($rest);
        }

        throw new \Exception("FIXME handle number: ".$n);
    }

    private static function mapToMultiplier($num, $size)
    {
        $map = [
            'hundra'    => 100,
            'tusen'     => 1000,
            'miljon'    => 1000000,
            'miljoner'  => 1000000,

            // https://sv.wikipedia.org/wiki/Miljard
            'miljard'   => 1000000000,
            'miljarder' => 1000000000,

            // https://sv.wikipedia.org/wiki/Biljon
            'biljon'    => 1000000000000,
            'biljoner'  => 1000000000000,

            // https://sv.wikipedia.org/wiki/Biljard_%28tal%29
            'biljard'   => 1000000000000000,
            'biljarder' => 1000000000000000,
        ];

        if (!array_key_exists($size, $map)) {
            return null;
        }

        $multiplier = $map[$size];

        return bcmul($num, $multiplier);
    }
}
