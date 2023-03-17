<?php

namespace Jundayw\LocationBasedServices\Point;

use Jundayw\LocationBasedServices\Traits\Macroable;

abstract class AbstractPoint
{
    use Macroable;

    protected $labelsLatitudeLongitude = [
        "东经" => "E",// [0,180]
        "西经" => "W",// [0,-180]
        "北纬" => "N",// [0,90]
        "南纬" => "S",// [0,-90]
    ];

    protected $units = [
        "度" => "°",
        "分" => "′",
        "秒" => "″",
    ];

    protected $lng = 0.0;
    protected $lat = 0.0;

    /**
     * @param string $format
     * @param bool $flip
     * @param int $decimals
     * @return array
     */
    public function format(string $format = "d°m′s″L", bool $flip = false, int $decimals = 2): array
    {
        $data = $this->parse($decimals, $flip);
        foreach ($data as &$items) {
            $locale = $format;
            foreach ($items as $key => $item) {
                $locale = str_replace($key, $item, $locale);
            }
            $items = $locale;
        }
        return $data;
    }

    /**
     * @param int $decimals
     * @param bool $flip
     * @return array
     */
    public function parse(int $decimals = 2, bool $flip = false): array
    {
        $parse = function($itude, $type) use ($decimals, $flip) {
            $label = [
                "longitude" => array_slice($flip ? array_flip($this->getLabelsLatitudeLongitude()) : $this->getLabelsLatitudeLongitude(), 0, 2),
                "latitude"  => array_slice($flip ? array_flip($this->getLabelsLatitudeLongitude()) : $this->getLabelsLatitudeLongitude(), 2, 2),
            ];
            $data  = [
                "DDD"   => $itude,
                "ddd"   => abs($itude),
                "D"     => intval($itude),
                "d"     => 1,
                "m"     => 60,
                "s"     => 60,
                "L" => $itude >= 0 ? reset($label[$type]) : end($label[$type]),
            ];
            foreach ($data as $key => $rate) {
                if (in_array($key, ["d", "m", "s"]) == false) {
                    continue;
                }
                $itude      *= $rate;
                $rate       = $key == "s" ? round($itude, $decimals) : intval($itude);
                $itude      -= $rate;
                $data[$key] = abs($rate);
            }
            return $data;
        };
        return [
            "longitude" => $parse($this->getLng(), "longitude"),
            "latitude"  => $parse($this->getLat(), "latitude"),
        ];
    }

    /**
     * @return array
     */
    public function getLabelsLatitudeLongitude(): array
    {
        return $this->labelsLatitudeLongitude;
    }

    /**
     * @param array $labelsLatitudeLongitude
     * @return AbstractPoint
     */
    public function setLabelsLatitudeLongitude(array $labelsLatitudeLongitude): AbstractPoint
    {
        $this->labelsLatitudeLongitude = $labelsLatitudeLongitude;
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
     * @return AbstractPoint
     */
    public function setUnits(array $units): AbstractPoint
    {
        $this->units = $units;
        return $this;
    }

    /**
     * @return float
     */
    public function getLng(): float
    {
        return $this->lng;
    }

    /**
     * @param float $lng
     * @return AbstractPoint
     */
    public function setLng(float $lng): AbstractPoint
    {
        $this->lng = $lng;
        return $this;
    }

    /**
     * @return float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     * @return AbstractPoint
     */
    public function setLat(float $lat): AbstractPoint
    {
        $this->lat = $lat;
        return $this;
    }
}
