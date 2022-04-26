<?php

namespace Jundayw\LocationBasedServices\Geographies;

/**
 * @see https://tool.lu/coordinate/
 *
 * Class Geography
 * @package Jundayw\LocationBasedServices\Geographies
 */
abstract class Geography
{
    protected $x_pi = M_PI * 3000.0 / 180;
    protected $pi   = M_PI;// π
    protected $max  = 6378137.0;// 椭球体长半轴
    protected $min  = 6356752.3142;// 椭球体短半轴
    protected $ee   = 0.0;// 椭圆的第一扁心率平方

    /**
     * @param float $longitude
     * @param float $latitude
     * @return bool
     */
    public function isInChina(float $longitude, float $latitude): bool
    {
        return $longitude >= 72.004 && $longitude <= 137.8347 && $latitude >= 0.8293 && $latitude <= 55.8271;
    }

    abstract public function encode(float $longitude, float $latitude): array;

    abstract public function decode(float $longitude, float $latitude): array;
}
