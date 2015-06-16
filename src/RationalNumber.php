<?php namespace MartinLindhe\NumberPresentation;

/**
 * Based on http://rosettacode.org/wiki/Convert_decimal_number_to_rational#C
 * http://stackoverflow.com/questions/95727/how-to-convert-floats-to-human-readable-fractions
 */
class RationalNumber
{
    protected $val;

    public function __construct($val)
    {
        if (is_string($val)) {
            $x = explode('/', $val);
            $this->val = $x[0] / $x[1];
        } else if (is_numeric($val)) {
            $this->val = $val;
        } else {
            throw new \Exception('bad input '.$val);
        }
    }

    /**
     * @return double
     */
    public function asDouble()
    {
        return $this->val;
    }

    /**
     * @param float $tolerance
     * @return string
     */
    public function asRational($tolerance = 1.e-6)
    {
        if ($this->val == (int) $this->val) {
            // integer
            return (string) $this->val;
        }

        $h1=1;
        $h2=0;
        $k1=0;
        $k2=1;
        $b = 1 / $this->val;

        do {
            $b = 1 / $b;
            $a = floor($b);
            $aux = $h1;
            $h1 = $a * $h1 + $h2;
            $h2 = $aux;
            $aux = $k1;
            $k1 = $a * $k1 + $k2;
            $k2 = $aux;
            $b = $b - $a;
        } while (abs($this->val-$h1/$k1) > $this->val * $tolerance);

        return $h1.'/'.$k1;
    }
}
