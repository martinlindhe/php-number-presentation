<?php namespace MartinLindhe\NumberPresentation;

/**
 * Helpers for displaying math stuff in UTF-8
 */
class MathSymbol
{
    protected static $superScript = [
        0 => '⁰',
        1 => '¹',
        2 => '²',
        3 => '³',
        4 => '⁴',
        5 => '⁵',
        6 => '⁶',
        7 => '⁷',
        8 => '⁸',
        9 => '⁹',
    ];

    protected static $subScript = [
        0 => '₀',
        1 => '₁',
        2 => '₂',
        3 => '₃',
        4 => '₄',
        5 => '₅',
        6 => '₆',
        7 => '₇',
        8 => '₈',
        9 => '₉',
    ];

    /**
     * @param int $n
     * @return string utf8
     */
    public static function superScriptNumber($n)
    {
        if ($n < 0) {
            return '⁻'.self::superScriptNumber(-$n);
        }

        if ($n >= 10) {
            $first = substr($n, 0, 1);
            $rest = substr($n, 1);

            return self::superScriptNumber($first).
            self::superScriptNumber($rest);
        }

        if (array_key_exists($n, self::$superScript)) {
            return self::$superScript[$n];
        }

        err('superScriptNumber missing for '.$n);
        return '';
    }

    /**
     * @param $s
     * @return int|null
     */
    public static function fromSuperScriptNumber($s)
    {
        if (mb_substr($s, 0, 1) == '⁻') {
            return -self::fromSuperScriptNumber(mb_substr($s, 1));
        }

        $len = mb_strlen($s);

        if ($len == 1) {
            $res = array_search($s, self::$superScript);
            if ($res === false) {
                err('not found: '.$s);
                return null;
            }
            return $res;
        }

        $last = mb_substr($s, -1);
        $res = array_search($last, self::$superScript);
        if ($res === false) {
            err('not found: '.$last);
            return null;
        }

        return (self::fromSuperScriptNumber(mb_substr($s, 0, -1)) * 10) + $res;
    }

    /**
     * @param int $n
     * @return string utf8
     */
    public static function subScriptNumber($n)
    {
        if ($n < 0) {
            return '₋'.self::subScriptNumber(-$n);
        }

        if ($n >= 10) {
            $first = substr($n, 0, 1);
            $rest = substr($n, 1);

            return self::subScriptNumber($first).
            self::subScriptNumber($rest);
        }

        if (array_key_exists($n, self::$subScript)) {
            return self::$subScript[$n];
        }

        err('subScriptNumber missing for '.$n);
        return '';
    }
}
