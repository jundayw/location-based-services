<?php

namespace Jundayw\LocationBasedServices\Converters;

use Jundayw\LocationBasedServices\Point\Point;

class Converter extends Point
{
    /**
     * Converter constructor.
     * @param float|string|null $lng
     * @param float|string|null $lat
     */
    public function __construct($lng = null, $lat = null)
    {
        if ($lng) {
            $this->setLng($lng);
        }
        if ($lat) {
            $this->setLat($lat);
        }
    }

    /**
     * @param float|string $dot
     * @return float
     */
    private function convertor($dot): float
    {
        // 统一坐标格式
        $keys     = array_merge(array_keys($this->labelsLatitudeLongitude), array_keys($this->units));
        $values   = array_merge(array_values($this->labelsLatitudeLongitude), array_values($this->units));
        $dot      = str_replace($keys, $values, $dot);
        $negative = true;
        // 判断东西经度及南北纬度
        foreach (["W", "S"] as $item) {
            if (substr_count($dot, $item)) {
                $negative = false;
            }
        }
        // 将度分秒(DMS)转换度(DDD)
        $dot   = str_replace(array_values($this->labelsLatitudeLongitude), "", $dot);
        $dot   = str_replace(array_values($this->units), "°", $dot);
        $dot   = explode("°", $dot);
        $point = 0.0;
        // 计算公式
        // 108度54分22.2秒 = 108+(54/60)+(22.2/3600) = 108.90616度
        foreach ($dot as $key => $item) {
            if ($point >= 0) {
                $point += floatval($item) / pow(60, $key);
            } else {
                $point -= floatval($item) / pow(60, $key);
            }
        }
        return round($negative ? $point : 0 - $point, 12);
    }

    /**
     * @param float|string|null $lat
     * @return Converter
     */
    public function setLat($lat): Converter
    {
        $this->lat = $this->convertor($lat);
        return $this;
    }

    /**
     * @param float|string|null $lng
     * @return Converter
     */
    public function setLng($lng): Converter
    {
        $this->lng = $this->convertor($lng);
        return $this;
    }
}
