<?php

namespace Jundayw\LocationBasedServices\Circles;

class Earth implements Circle
{
    // 椭球体长半轴（单位：米）
    private $radius = 6378137.0;
    
    /**
     * Earth constructor.
     * @param float|null $radius
     */
    public function __construct(float $radius = null)
    {
        $this->radius = $radius ?? $this->radius;
    }

    /**
     * @return float
     */
    public function getRadius(): float
    {
        return $this->radius;
    }

    /**
     * @param float $radius
     * @return Earth
     */
    public function setRadius(float $radius): Earth
    {
        $this->radius = $radius;
        return $this;
    }
}
