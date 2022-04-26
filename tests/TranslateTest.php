<?php

namespace Jundayw\LocationBasedServices;

use Jundayw\LocationBasedServices\Geographies\BD09;
use Jundayw\LocationBasedServices\Geographies\GCJ02;
use Jundayw\LocationBasedServices\Geographies\WGS84;
use Jundayw\LocationBasedServices\Point\Point;
use PHPUnit\Framework\TestCase;

class TranslateTest extends TestCase
{
    private $context;

    protected function setUp(): void
    {
        $this->context = new Point();
        $this->context->setLng(116.0);
        $this->context->setLat(39.0);
    }

    public function pointProvider()
    {
        return [
            [
                [
                    WGS84::class,
                    GCJ02::class,
                ],
                new Point(116.00601802564, 39.000885589439),
            ],// GPS转火星坐标系
            [
                [
                    WGS84::class,
                    BD09::class,
                ],
                new Point(116.01254312253, 39.006813849154),
            ],// GPS转百度坐标系
            [
                [
                    GCJ02::class,
                    BD09::class,
                ],
                new Point(116.00655849987, 39.005825999956),
            ],// 火星坐标系转百度坐标系
            [
                [
                    BD09::class,
                    BD09::class,
                ],
                new Point(116.0, 39.0),
            ],// 相同坐标系转换
            [
                [
                    BD09::class,
                    GCJ02::class,
                ],
                new Point(115.99341690406, 38.994266575726),
            ],// 百度坐标系转火星坐标系
            [
                [
                    BD09::class,
                    WGS84::class,
                ],
                new Point(115.98742185504, 38.993404469585),
            ],// 百度坐标系转GPS坐标系
        ];
    }

    /**
     * @dataProvider pointProvider
     */
    public function testTranslate($routes = [], $expectedResult)
    {
        $translate = new Translate($routes[0], $routes[1]);
        $point     = $translate->translate($this->context);
        $this->assertTrue(bccomp($point->getLng(), $expectedResult->getLng(), 12) == 0 && bccomp($point->getLat(), $expectedResult->getLat(), 12) == 0);
    }
}
