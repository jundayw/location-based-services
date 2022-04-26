<?php

namespace Jundayw\LocationBasedServices\Units;

interface Units
{
    public function unit();

    public function getUnit(): string;

    public function setUnit(string $unit): Units;

    public function getUnits(): array;

    public function setUnits(array $units): Units;
}
