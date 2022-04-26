<?php

namespace Jundayw\LocationBasedServices;

use Jundayw\LocationBasedServices\Converters\Converter;
use Jundayw\LocationBasedServices\Point\Point;
use Jundayw\LocationBasedServices\Units\LengthUnit;
use Jundayw\LocationBasedServices\Units\Units;
use PHPUnit\Framework\TestCase;

class PositionTest extends TestCase
{
    public function distanceProvider()
    {
        return [
            [
                [
                    new Converter("东经98度58分45.8秒", "南纬24度45分16.1秒"),
                    new Point(90.979388888889, -20.754472222222),
                ],
                new LengthUnit("KM"),
                [933.88, "933.88KM"],
            ],
            [
                [
                    new Converter("东经98度58分45.8秒", "南纬24度45分16.1秒"),
                    new Converter(90.979388888889, -20.754472222222),
                ],
                new LengthUnit("KM"),
                [933.88, "933.88KM"],
            ],
            [
                [
                    new Converter("东经98度58分45.8秒", "南纬24度45分16.1秒"),
                    new Converter("东经90度58分45.8秒", "南纬20度45分16.1秒"),
                ],
                new LengthUnit("KM"),
                [933.88, "933.88KM"],
            ],
            [
                [
                    new Converter("东经98度58分45.8秒", "南纬24度45分16.1秒"),
                    new Converter("90°58′45.8″E", "20°45′16.1″S"),
                ],
                new LengthUnit("KM"),
                [933.88, "933.88KM"],
            ],
            [
                [
                    new Converter("东经98度58分45.8秒", "南纬24度45分16.1秒"),
                    new Converter("E90°58′45.8″", "S20°45′16.1″"),
                ],
                new LengthUnit("KM"),
                [933.88, "933.88KM"],
            ],
            [
                [
                    new Converter("东经98度58分45.8秒", "南纬24度45分16.1秒"),
                    new Converter("90°58′45.8″", "-20°45′16.1″"),
                ],
                new LengthUnit("KM"),
                [933.88, "933.88KM"],
            ],
        ];
    }

    /**
     * @dataProvider distanceProvider
     */
    public function testGetDistance($attribute, Units $units, $expectedResult)
    {
        $position = new Position($units);
        $this->assertSame($expectedResult[0], $position->getDistance($attribute[0], $attribute[1]));
    }

    /**
     * @dataProvider distanceProvider
     */
    public function testGetDistanceUnit($attribute, Units $units, $expectedResult)
    {
        $position = new Position($units);
        $this->assertSame($expectedResult[1], $position->getDistanceUnit($attribute[0], $attribute[1]));
    }
}
