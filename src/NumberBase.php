<?php namespace MartinLindhe\NumberPresentation;

class NumberBase
{
    const BINARY = 2;
    const OCTAL = 8;
    const DECIMAL = 10;
    const HEXADECIMAL = 16;
    const VIGESIMAL = 20;

    /**
     * @var mixed current value
     */
    protected $value;

    /**
     * @var string unit name
     */
    protected $unit;

    /**
     * Creates a new number base conversion.
     * @param string $value value
     * @param int $unit base name, see class constants
     */
    public function __construct($value = null, $unit = null)
    {
        if ($unit !== null) {
            $this->setUnit($unit);
        }

        if ($unit === null && $value !== null) {
            $this->setUnit(self::getNumberBaseFromString($value));
        }

        if ($value !== null) {
            $this->setValue($value);
        }
    }

    /**
     * Sets the value
     * @param string $value value
     * @return NumberBase
     */
    public function setValue($value)
    {
        $this->value = $this->parseValue($value);
        return $this;
    }

    /**
     * Sets the unit
     * @param string $unit unit name
     * @return NumberBase
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
        return $this;
    }

    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Recognize current number base of string
     * @param string $value
     * @return int
     * @throws \Exception
     */
    public static function getNumberBaseFromString($value)
    {
        if (substr($value, 0, 1) == 'b') {
            return self::BINARY;
        }

        if (substr($value, 0, 1) == 'o') {
            return self::OCTAL;
        }

        if (substr($value, 0, 2) == '0x') {
            return self::HEXADECIMAL;
        }

        if (preg_match('/^([0-9])+$/', $value)) {
            return self::DECIMAL;
        }

        if (preg_match('/^([A-Fa-f0-9])+$/', $value)) {
            return self::HEXADECIMAL;
        }

        throw new \Exception ("not a number ".$value);
    }

    protected function parseValue($value)
    {
        if ($this->unit == self::BINARY && substr($value, 0, 1) == 'b') {
            return substr($value, 1);
        } else if ($this->unit == self::OCTAL && substr($value, 0, 1) == 'o') {
            return substr($value, 1);
        } else if ($this->unit == self::HEXADECIMAL && substr($value, 0, 2) == '0x') {
            return substr($value, 2);
        }

        return $value;
    }

    /**
     * Converts this quantity to another unit.
     * @param int $unit unit to convert to
     * @return NumberBase
     */
    public function to($unit)
    {
        $this->value = $this->convert($unit, $this->value);
        $this->unit = $unit;
        return $this;
    }

    /**
     * Converts the value to another unit.
     * @param int $to unit to convert to
     * @return float converted value
     */
    public function convert($to)
    {
        return base_convert($this->value, $this->unit, $to);
    }

    /**
     * Magic function for outputting this quantity.
     * @return string this quantity as a string
     */
    public function __toString()
    {
        if ($this->unit == self::BINARY) {
            return 'b'.$this->value;
        }
        if ($this->unit == self::OCTAL) {
            return 'o'.$this->value;
        }
        if ($this->unit == self::HEXADECIMAL) {
            return '0x'.$this->value;
        }

        return $this->value;
    }
}
