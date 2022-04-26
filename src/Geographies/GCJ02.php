<?php

namespace Jundayw\LocationBasedServices\Geographies;

class GCJ02 extends Geography
{
    /**
     * @param float $max
     * @param float $min
     */
    public function __construct(float $max = 6378245.0, float $min = 6356863.0188)
    {
        $this->max = $max ?? $this->max;
        $this->min = $min ?? $this->min;
        $this->ee  = pow(sqrt(pow($this->max, 2) - pow($this->min, 2)) / $this->max, 2);
    }

    /**
     * WGS84转GCJ02(火星坐标系)
     *
     * @param float $longitude WGS84坐标系的经度
     * @param float $latitude WGS84坐标系的纬度
     * @return array
     */
    public function encode(float $longitude, float $latitude): array
    {
        if ($this->isInChina($longitude, $latitude) == false) {
            return [$longitude, $latitude];
        }
        $dlat      = $this->transformLatitude($longitude - 105.0, $latitude - 35.0);
        $dlng      = $this->transformLongitude($longitude - 105.0, $latitude - 35.0);
        $radlat    = $latitude / 180.0 * M_PI;
        $magic     = sin($radlat);
        $magic     = 1 - $this->ee * $magic * $magic;
        $sqrtmagic = sqrt($magic);
        $dlat      = ($dlat * 180.0) / (($this->max * (1 - $this->ee)) / ($magic * $sqrtmagic) * M_PI);
        $dlng      = ($dlng * 180.0) / ($this->max / $sqrtmagic * cos($radlat) * M_PI);
        $mglat     = $latitude + $dlat;
        $mglng     = $longitude + $dlng;
        return [$mglng, $mglat];
    }

    /**
     * GCJ02(火星坐标系)转GPS84
     *
     * @param float $longitude 火星坐标系的经度
     * @param float $latitude 火星坐标系纬度
     * @return array
     */
    public function decode(float $longitude, float $latitude): array
    {
        if ($this->isInChina($longitude, $latitude) == false) {
            return [$longitude, $latitude];
        }
        $dlat      = $this->transformLatitude($longitude - 105.0, $latitude - 35.0);
        $dlng      = $this->transformLongitude($longitude - 105.0, $latitude - 35.0);
        $radlat    = $latitude / 180.0 * M_PI;
        $magic     = sin($radlat);
        $magic     = 1 - $this->ee * $magic * $magic;
        $sqrtmagic = sqrt($magic);
        $dlat      = ($dlat * 180.0) / (($this->max * (1 - $this->ee)) / ($magic * $sqrtmagic) * M_PI);
        $dlng      = ($dlng * 180.0) / ($this->max / $sqrtmagic * cos($radlat) * M_PI);
        $mglat     = $latitude + $dlat;
        $mglng     = $longitude + $dlng;
        return [$longitude * 2 - $mglng, $latitude * 2 - $mglat];
    }

    /**
     * 经度转换
     *
     * @param float $longitude
     * @param float $latitude
     * @return float
     */
    protected function transformLongitude(float $longitude, float $latitude): float
    {
        $ret = 300.0 + $longitude + 2.0 * $latitude + 0.1 * $longitude * $longitude + 0.1 * $longitude * $latitude + 0.1 * sqrt(abs($longitude));
        $ret += (20.0 * sin(6.0 * $longitude * M_PI) + 20.0 * sin(2.0 * $longitude * M_PI)) * 2.0 / 3.0;
        $ret += (20.0 * sin($longitude * M_PI) + 40.0 * sin($longitude / 3.0 * M_PI)) * 2.0 / 3.0;
        $ret += (150.0 * sin($longitude / 12.0 * M_PI) + 300.0 * sin($longitude / 30.0 * M_PI)) * 2.0 / 3.0;
        return $ret;
    }

    /**
     * 纬度转换
     *
     * @param float $longitude
     * @param float $latitude
     * @return float
     */
    protected function transformLatitude(float $longitude, float $latitude): float
    {
        $ret = -100.0 + 2.0 * $longitude + 3.0 * $latitude + 0.2 * $latitude * $latitude + 0.1 * $longitude * $latitude + 0.2 * sqrt(abs($longitude));
        $ret += (20.0 * sin(6.0 * $longitude * M_PI) + 20.0 * sin(2.0 * $longitude * M_PI)) * 2.0 / 3.0;
        $ret += (20.0 * sin($latitude * M_PI) + 40.0 * sin($latitude / 3.0 * M_PI)) * 2.0 / 3.0;
        $ret += (160.0 * sin($latitude / 12.0 * M_PI) + 320 * sin($latitude * M_PI / 30.0)) * 2.0 / 3.0;
        return $ret;
    }
}
