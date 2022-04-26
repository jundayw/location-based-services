<?php

namespace Jundayw\LocationBasedServices\Geographies;

class BD09 extends Geography
{
    /**
     * 火星坐标系(GCJ-02)转百度坐标系(BD-09)
     *
     * @param float $longitude 火星坐标经度
     * @param float $latitude 火星坐标纬度
     * @return array
     */
    public function encode(float $longitude, float $latitude): array
    {
        if ($this->isInChina($longitude, $latitude) == false) {
            return [$longitude, $latitude];
        }
        $z     = sqrt($longitude * $longitude + $latitude * $latitude) + 0.00002 * sin($latitude * $this->x_pi);
        $theta = atan2($latitude, $longitude) + 0.000003 * cos($longitude * $this->x_pi);
        $lng   = $z * cos($theta) + 0.0065;
        $lat   = $z * sin($theta) + 0.006;
        return [$lng, $lat];
    }

    /**
     * 百度坐标系(BD-09)转火星坐标系(GCJ-02)
     *
     * @param float $longitude 百度坐标纬度
     * @param float $latitude 百度坐标经度
     * @return array
     */
    public function decode(float $longitude, float $latitude): array
    {
        if ($this->isInChina($longitude, $latitude) == false) {
            return [$longitude, $latitude];
        }
        $x     = $longitude - 0.0065;
        $y     = $latitude - 0.006;
        $z     = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $this->x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $this->x_pi);
        $lng   = $z * cos($theta);
        $lat   = $z * sin($theta);
        return [$lng, $lat];
    }
}
