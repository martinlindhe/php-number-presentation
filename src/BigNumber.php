<?php namespace MartinLindhe\NumberPresentation;

class BigNumber
{
    /** @var string */
    var $value;

    var $scale = 12;

    public function __construct($s)
    {
        $this->value = $s;
    }

    /**
     * @param BigNumber|string $n
     * @return $this
     */
    public function add($n)
    {
        $this->value = bcadd($this->value, $this->parseValue($n), $this->scale);
        return $this;
    }

    /**
     * @param BigNumber|string $n
     * @return $this
     */
    public function sub($n)
    {
        $this->value = bcsub($this->value, $this->parseValue($n), $this->scale);
        return $this;
    }

    /**
     * @param BigNumber|string $n
     * @return $this
     */
    public function mul($n)
    {
        $this->value = bcmul($this->value, $this->parseValue($n), $this->scale);
        return $this;
    }

    /**
     * @param BigNumber|string $n
     * @return $this
     */
    public function div($n)
    {
        $this->value = bcdiv($this->value, $this->parseValue($n), $this->scale);
        return $this;
    }

    /**
     * @param BigNumber|string $n
     * @return $this
     */
    public function pow($n)
    {
        $this->value = bcpow($this->value, $this->parseValue($n), $this->scale);
        return $this;
    }

    /**
     * @return $this
     */
    public function sqrt()
    {
        $this->value = bcsqrt($this->value, $this->scale);
        return $this;
    }

    /**
     * @param BigNumber|string|float $n
     * @return string
     * @throws \Exception
     */
    private function parseValue($n)
    {
        if ($n instanceof BigNumber) {
            return $n->value;
        } else if (is_string($n)) {
            return $n;
        } else if (is_int($n)) {
            return ''.$n;
        } else if (is_float($n)) {
            return ''.$n;
        } else {
            d(gettype($n));
            d($n);
            throw new \Exception;
        }
    }

    public function __toString()
    {
        return self::stripTrailingZeroes($this->value);
    }

    /**
     * @param string $s
     * @return string
     */
    public static function stripTrailingZeroes($s)
    {
        $pos = strpos($s, '.');
        if ($pos === false) {
            // integer
            return $s;
        }

        for ($i = strlen($s)-1; $i > 0; $i--) {
            $b = substr($s, $i, 1);
            if ($b != '0') {
                if ($b == '.') {
                    //d('OUT 0 (b is '.$b.'): '.substr($s, 0, $i));
                    return substr($s, 0, $i);
                }
                if ($i > 0 && substr($s, $i - 1, 1) == '.') {
                    //d('OUT 1:' .substr($s, 0, $i + 1));
                    return substr($s, 0, $i + 1);
                }
                //d('OUT 2 (b is '.$b.'): '.substr($s, 0, $i+1));
                return substr($s, 0, $i + 1);
            }
        }
        //d('OUT:' .$s);
        return $s;
    }
}
