<?php

namespace Jundayw\LocationBasedServices;

use Jundayw\LocationBasedServices\Circles\Circle;
use Jundayw\LocationBasedServices\Circles\Earth;
use Jundayw\LocationBasedServices\Point\AbstractPoint;
use Jundayw\LocationBasedServices\Traits\Macroable;
use Jundayw\LocationBasedServices\Units\LengthUnit;
use Jundayw\LocationBasedServices\Units\Units;

class Position
{
    use Macroable;

    private $circle   = null;
    private $units    = null;
    private $decimals = 2;

    /**
     * Position constructor.
     * @param Units|null $units
     * @param Circle|null $circle
     */
    public function __construct(Units $units = null, Circle $circle = null)
    {
        $this->units  = $units ?? new LengthUnit();
        $this->circle = $circle ?? new Earth();
    }

    /**
     * 将角度换算为弧度
     *
     * @param float $radius
     * @return float
     */
    public function radius(float $radius): float
    {
        return $radius * M_PI / 180.0;
    }

    /**
     * 获取两点距离
     *
     * @param AbstractPoint $start
     * @param AbstractPoint $end
     * @return float
     *
     * @see https://blog.csdn.net/kelinfeng16/article/details/98030330
     * @see https://blog.csdn.net/matriculate/article/details/6248296
     * @see https://blog.csdn.net/feinifi/article/details/101284874
     * @see https://blog.csdn.net/haochajin/article/details/102709282
     * @see https://blog.csdn.net/chengqiuming/article/details/121046989
     */
    public function getDistance(AbstractPoint $start, AbstractPoint $end): float
    {
        $startLongitude = $this->radius($start->getLng());
        $startLatitude  = $this->radius($start->getLat());
        $endLongitude   = $this->radius($end->getLng());
        $endLatitude    = $this->radius($end->getLat());

        $diffLatitude  = $startLatitude - $endLatitude;
        $diffLongitude = $startLongitude - $endLongitude;

        // the great circle distance in radians
        // great circle就是一个球体上的切面，它的圆心即是球心的一个周长最大的圆。
        $radians  = pow(sin($diffLatitude / 2), 2) + cos($startLatitude) * cos($endLatitude) * pow(sin($diffLongitude / 2), 2);
        $distance = 2 * ($this->circle->getRadius() / $this->getUnits()->unit()) * asin(sqrt($radians));// 计算距离
        return $this->format($distance, $this->getDecimals());
    }

    /**
     * 获取两点距离附加单位
     *
     * @param AbstractPoint $start
     * @param AbstractPoint $end
     * @return string
     */
    public function getDistanceUnit(AbstractPoint $start, AbstractPoint $end): string
    {
        return join("", [$this->getDistance($start, $end), $this->units->getUnit()]);
    }

    /**
     * 格式化
     *
     * @param float $number
     * @param int $length
     * @return float
     */
    private function format(float $number, int $length): float
    {
        $number    = round($number, $length);
        $number    = explode(".", sprintf("%s.0", $number));
        $number[1] = str_pad($number[1], $length, "0", STR_PAD_RIGHT);
        return join(".", array_slice($number, 0, 2)) * 1.0;
    }

    /**
     * @return Circle
     */
    public function getCircle(): Circle
    {
        return $this->circle;
    }

    /**
     * @param Circle $circle
     * @return Position
     */
    public function setCircle(Circle $circle): Position
    {
        $this->circle = $circle;
        return $this;
    }

    /**
     * @return Units
     */
    public function getUnits(): Units
    {
        return $this->units;
    }

    /**
     * @param Units $units
     * @return Position
     */
    public function setUnits(Units $units): Position
    {
        $this->units = $units;
        return $this;
    }

    /**
     * @return int
     */
    public function getDecimals(): int
    {
        return $this->decimals;
    }

    /**
     * @param int $decimals
     * @return Position
     */
    public function setDecimals(int $decimals): Position
    {
        $this->decimals = $decimals;
        return $this;
    }
}
