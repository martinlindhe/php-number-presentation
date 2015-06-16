<?php namespace MartinLindhe\NumberPresentation;

// XXX implement iterable or something, how to be an array class ?!

class NumberList
{
    /**
     * Parse a string describing 1 or more numbers into an numeric array
     * @param string $s like "1, 2 och 3" or "1, 2, 3"
     * @return string[]
     * @throws \Exception
     */
    public static function parseNumbersToArray($s)
    {
        $s = str_replace(' och ', ', ', $s);

        $x = explode(',', $s);

        $res = [];

        foreach ($x as $val) {
            $val = trim($val);
            $val = NaturalNumberSwedish::parse($val);

            $res[] = ''.$val;
        }

        return $res;
    }
}
