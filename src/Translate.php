<?php

namespace Jundayw\LocationBasedServices;

use Jundayw\LocationBasedServices\Geographies\BD09;
use Jundayw\LocationBasedServices\Geographies\GCJ02;
use Jundayw\LocationBasedServices\Geographies\Geography;
use Jundayw\LocationBasedServices\Geographies\WGS84;
use Jundayw\LocationBasedServices\Point\AbstractPoint;
use Jundayw\LocationBasedServices\Traits\Macroable;

class Translate
{
    use Macroable;

    private $input  = WGS84::class;
    private $output = WGS84::class;
    private $routes = [];

    /**
     * Translate constructor.
     * @param string $input
     * @param string $output
     */
    public function __construct(string $input = null, string $output = null)
    {
        $this->input  = $input ?? $this->input;
        $this->output = $output ?? $this->output;
        // 基础编码坐标系到高级编码坐标系顺序
        $this->routes = [
            new WGS84(),
            new GCJ02(),
            new BD09(),
        ];
    }

    /**
     * 坐标系转换
     *
     * @param AbstractPoint $point
     * @return AbstractPoint
     */
    public function translate(AbstractPoint $point): AbstractPoint
    {
        // 获取转化类全限定类名
        $routes = array_map(function ($route) {
            return get_class($route);
        }, $this->routes);
        // 查找起始位置
        $input  = array_search($this->input, $routes);
        $output = array_search($this->output, $routes);
        if ($input == $output) {
            return $point;
        }
        // 基础坐标系到高级坐标系编码
        // 高级坐标系到基础坐标系解码
        $method = $input < $output ? "encode" : "decode";
        $routes = array_filter($this->routes, function ($v, $k) use ($input, $output) {
            if ($input < $output) {
                return $k > $input && $k <= $output;
            }
            return $k <= $input && $k > $output;
        }, ARRAY_FILTER_USE_BOTH);
        // 解密方法需要倒序顺序执行
        if ($method == "decode") {
            krsort($routes);
        }
        $longitude = $point->getLng();
        $latitude  = $point->getLat();
        foreach ($routes as $route) {
            [$longitude, $latitude] = $route->$method($longitude, $latitude);
        }
        $longitude = round($longitude, 12);
        $latitude  = round($latitude, 12);
        return (clone $point)->setLng($longitude)->setLat($latitude);
    }

    /**
     * @return string
     */
    public function getInput(): string
    {
        return $this->input;
    }

    /**
     * @param string $input
     * @return Translate
     */
    public function setInput(string $input): Translate
    {
        $this->input = $input;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutput(): string
    {
        return $this->output;
    }

    /**
     * @param string $output
     * @return Translate
     */
    public function setOutput(string $output): Translate
    {
        $this->output = $output;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @param array $routes
     * @return Translate
     */
    public function setRoutes(array $routes): Translate
    {
        $this->routes = $routes;
        return $this;
    }
}
