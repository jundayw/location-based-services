<?php

namespace Jundayw\LocationBasedServices\Units;

class LengthUnit implements Units
{
    private $unit = "M";

    private $units = [
        "KM" => 1000.0,
        "M"  => 1.0,
        "DM" => 0.1,
        "CM" => 0.01,
        "MM" => 0.001,
    ];

    /**
     * LengthUnit constructor.
     * @param string $unit
     * @param array $units
     */
    public function __construct(string $unit = null, array $units = [])
    {
        if ($unit) {
            $this->setUnit($unit);
        }
        if ($units) {
            $this->setUnits($units);
        }
    }

    /**
     * @return mixed|null
     */
    public function unit()
    {
        return array_key_exists($this->unit, $this->units) ? $this->units[$this->unit] : null;
    }

    /**
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     * @return LengthUnit
     */
    public function setUnit(string $unit): LengthUnit
    {
        $this->unit = strtoupper($unit);
        return $this;
    }

    /**
     * @return array
     */
    public function getUnits(): array
    {
        return $this->units;
    }

    /**
     * @param array $units
     * @return LengthUnit
     */
    public function setUnits(array $units): LengthUnit
    {
        $this->units = array_change_key_case($units, CASE_UPPER);
        return $this;
    }
}
