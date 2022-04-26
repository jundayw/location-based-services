<?php

namespace Jundayw\LocationBasedServices;

use Jundayw\LocationBasedServices\Converters\Converter;
use Jundayw\LocationBasedServices\Point\AbstractPoint;
use Jundayw\LocationBasedServices\Point\Point;
use PHPUnit\Framework\TestCase;

class PointTest extends TestCase
{
    private $context;

    protected function setUp(): void
    {
        $this->context = new Point();
    }

    public function pointProvider()
    {
        return [
            [
                [116.4028930664, 39.9034155951],
                new Point(116.4028930664, 39.9034155951),
            ],
            [
                [116.4028930664, 39.9034155951],
                new Converter(116.4028930664, 39.9034155951),
            ],
        ];
    }

    public function convertorProvider()
    {
        return [
            [
                [90.979166666667, -20.754444444444],
                new Converter("东经90度58分45秒", "南纬20度45分16秒"),
            ],
            [
                [90.979166666667, -20.754444444444],
                new Converter("90°58′45″E", "20°45′16″S"),
            ],
            [
                [90.979166666667, -20.754444444444],
                new Converter("90°58′45″", "-20°45′16″"),
            ],
        ];
    }

    public function formatProvider()
    {
        return [
            [
                new Point(90.979166666667, -20.754444444444),
                ["d°m′s″label", false],
                [
                    "longitude" => "90°58′45″E",
                    "latitude"  => "20°45′16″S",
                ],
            ],
            [
                new Converter(90.979166666667, -20.754444444444),
                ["label d°m′s″", false],
                [
                    "longitude" => "E 90°58′45″",
                    "latitude"  => "S 20°45′16″",
                ],
            ],
            [
                new Converter(90.979166666667, -20.754444444444),
                ["labeld度m分s秒", true],
                [
                    "longitude" => "东经90度58分45秒",
                    "latitude"  => "南纬20度45分16秒",
                ],
            ],
            [
                new Converter(90.979166666667, -20.754444444444),
                ["D°m′s″", false],
                [
                    "longitude" => "90°58′45″",
                    "latitude"  => "-20°45′16″",
                ],
            ],
            [
                new Converter("东经90度58分45秒", "南纬20度45分16秒"),
                ["DDD", false],
                [
                    "longitude" => "90.979166666667",
                    "latitude"  => "-20.754444444444",
                ],
            ],
            [
                new Converter("东经90度58分45秒", "南纬20度45分16秒"),
                ["d°m′s″label", false],
                [
                    "longitude" => "90°58′45″E",
                    "latitude"  => "20°45′16″S",
                ],
            ],
        ];
    }

    /**
     * @dataProvider pointProvider
     */
    public function testPoint($attribute, Point $point)
    {
        $this->context->setLng($attribute[0]);
        $this->context->setLat($attribute[1]);
        $this->assertTrue($this->context->getLng() == $point->getLng() && $this->context->getLat() == $point->getLat());
    }

    /**
     * @dataProvider convertorProvider
     */
    public function testConvertor($attribute, Converter $point)
    {
        $this->context->setLng($attribute[0]);
        $this->context->setLat($attribute[1]);
        $this->assertTrue($this->context->getLng() == $point->getLng() && $this->context->getLat() == $point->getLat());
    }

    /**
     * @dataProvider formatProvider
     */
    public function testFormat(AbstractPoint $point, $attribute, $expectedResult)
    {
        $format = $point->format($attribute[0], $attribute[1]);
        $this->assertSame($expectedResult, $format);
    }
}
