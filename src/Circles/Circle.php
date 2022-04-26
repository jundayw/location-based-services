<?php

namespace Jundayw\LocationBasedServices\Circles;

interface Circle
{
    public function getRadius(): float;

    public function setRadius(float $radius): Circle;
}
