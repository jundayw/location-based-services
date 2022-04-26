<?php

namespace Jundayw\LocationBasedServices\Point;

class Point extends AbstractPoint
{
    /**
     * Point constructor.
     * @param float|null $lng
     * @param float|null $lat
     */
    public function __construct(float $lng = null, float $lat = null)
    {
        if ($lng) {
            $this->setLng($lng);
        }
        if ($lat) {
            $this->setLat($lat);
        }
    }
}
