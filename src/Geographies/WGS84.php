<?php

namespace Jundayw\LocationBasedServices\Geographies;

class WGS84 extends Geography
{
    public function encode(float $longitude, float $latitude): array
    {
        return [$longitude, $latitude];
    }

    public function decode(float $longitude, float $latitude): array
    {
        return [$longitude, $latitude];
    }
}
