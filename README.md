## 基于椭球体坐标及坐标系转换和计算服务（Location Based Services，LBS）

* 可扩展任意椭球体计算：需实现 Circle 接口；
* 可扩展任意椭球体坐标系转换：需继承 Geography 抽象类；
* 可扩展任意椭球体计算方法：默认只支持两坐标距离计算，可扩展实现三角形、正方形、长方形、梯形面积计算；
* 可扩展计算单位：默认支持长度单位，可扩展面积单位，需实现 Units 接口；
* 可扩展任意地理坐标系：北京 54、西安 80、 WGS84、GCJ-02、BD-09、GCS2000
* 可扩展任意语言：默认支持中文英文，可自定义其他语言；

## [地心坐标系及椭球常识](documentor.md)

# 安装方法

命令行下, 执行 composer 命令安装:

```
composer require jundayw/location-based-services
```

[![Latest Stable Version](http://poser.pugx.org/jundayw/location-based-services/v)](https://packagist.org/packages/jundayw/location-based-services) 
[![Total Downloads](http://poser.pugx.org/jundayw/location-based-services/downloads)](https://packagist.org/packages/jundayw/location-based-services) 
[![Latest Unstable Version](http://poser.pugx.org/jundayw/location-based-services/v/unstable)](https://packagist.org/packages/jundayw/location-based-services) 
[![License](http://poser.pugx.org/jundayw/location-based-services/license)](https://packagist.org/packages/jundayw/location-based-services) 
[![PHP Version Require](http://poser.pugx.org/jundayw/location-based-services/require/php)](https://packagist.org/packages/jundayw/location-based-services)

# 坐标计算

```
├─Position.php              // 通过坐标计算距离
├─Circles
│      Circle.php           // 接口
│      Earth.php            // 地球相关参数
├─Point
│      AbstractPoint.php    // 将标准坐标格式转换为不同坐标格式
│      Point.php            // 标准坐标格式
├─Converters
│      Converter.php        // 将不同坐标格式转换为标准坐标格式
└─Units
        LengthUnit.php      // 长度单位换算
        Units.php           // 接口
```

## 支持坐标格式

```php
// 标准坐标格式
$points    = [
    [116.4028930664, 39.9034155951],
    [114.3347167969, 30.5433389542],
];
// 其他支持的坐标格式
$points    = [
    ["东经98度2分43秒", "南纬24度45分16.1秒"],
    ["98°2′43.8″E", "24°45′16.1″S"],
    ["E98°2′43.8″", "S24°45′16.1″"],
    ["98°59′59.9″E", "24°59′59.9″N"],
    ["98°59′59.9″W", "24°59′59.9″S"],
    ["98.5°E", "24.5°S"],
    ["98.5E", "24.5S"],
    ["98.5", "-24.5"],
];
```

## 计算两坐标间距离

```php
$position = new Position();
$position->setDecimals(2);
// 标准坐标计算距离
$distance = $position->getDistance(
    new Point(116.4028930664, 39.9034155951),
    new Point(114.3347167969, 30.5433389542)
);
// 其他格式标准计算距离
$distance = $position->getDistance(
    new Converter("东经98度58分45.8秒", "南纬24度45分16.1秒"),
    new Converter("116°23′28.4″E", "39°54′26.2″N")
);
```

## 计算两坐标间距离（附加单位）

```php
$position = new Position(new LengthUnit("KM"));
$position->setDecimals(2);
// 标准坐标计算距离
$distance = $position->getDistanceUnit(
    new Point(116.4028930664, 39.9034155951),
    new Point(114.3347167969, 30.5433389542)
);
// 其他格式标准计算距离
$distance = $position->getDistanceUnit(
    new Converter("东经98度58分45.8秒", "南纬24度45分16.1秒"),
    new Converter("116°23′28.4″E", "39°54′26.2″N")
);
```

## 坐标解析

### 英文标签

```php
$point = new Point(98.979388888889, -24.754472222222);
$point = new Converter("东经98度58分45.8秒", "南纬24度45分16.1秒");
var_dump($point->parse());
```
输出结果：
```php
array (
  'longitude' => array (
        'DDD' => 98.979388888889,
        'ddd' => 98.979388888889,
        'D' => 98,
        'd' => 98,
        'm' => 58,
        's' => 45.8,
        'label' => 'E',
  ),
  'latitude' => array (
        'DDD' => -24.754472222222,
        'ddd' => 24.754472222222,
        'D' => -24,
        'd' => 24,
        'm' => 45,
        's' => 16.1,
        'label' => 'S',
  ),
)
```

### 中文标签

```php
$point = new Point(98.979388888889, -24.754472222222);
$point = new Converter("东经98度58分45.8秒", "南纬24度45分16.1秒");
var_dump($point->parse(2, true));
```
输出结果：
```php
array (
  'longitude' => array (
        'DDD' => 98.979388888889,
        'ddd' => 98.979388888889,
        'D' => 98,
        'd' => 98,
        'm' => 58,
        's' => 45.8,
        'label' => '东经',
  ),
  'latitude' => array (
        'DDD' => -24.754472222222,
        'ddd' => 24.754472222222,
        'D' => -24,
        'd' => 24,
        'm' => 45,
        's' => 16.1,
        'label' => '南纬',
  ),
)
```

## 坐标格式转换

可根据具体需求转换需要的格式，支持标签：DDD、ddd、D、d、m、s、label。label中文情况下支持：东经、西经、北纬、南纬；英文情况下支持：E、W、N、S。

### 中文标签

```php
$point = new Point(98.979388888889, -24.754472222222);
$point = new Converter("东经98度58分45.8秒", "南纬24度45分16.1秒");
var_export($point->format("labeld度m分s秒", true));
```
输出结果：
```php
array (
  'longitude' => '东经98度58分45.8秒',
  'latitude' => '南纬24度45分16.1秒',
)
```

### 英文标签

```php
$point = new Point(98.979388888889, -24.754472222222);
$point = new Converter("东经98度58分45.8秒", "南纬24度45分16.1秒");
var_export($point->format());
```
输出结果：
```php
array (
  'longitude' => '98°58′45.8″E',
  'latitude' => '24°45′16.1″S',
)
```

# 坐标系转换

```
├─Translate.php             // 坐标系转换，如：GPS、火星坐标(Google/高德)、百度坐标系
└─Geographies
       BD09.php             // 百度坐标系编码、解码
       GCJ02.php            // 火星坐标系编码、解码
       Geography.php        // 坐标系基础配置
       WGS84.php            // GPS坐标系编码、解码
```

## 坐标系转换：标准坐标格式

```php
$points = [
    [116.4028930664, 39.9034155951],
    [114.3347167969, 30.5433389542],
];

foreach ($points as $point) {
    $point = new Point($point[0], $point[1]);
}
// WGS84(GPS坐标系)/GCJ02(火星坐标系)/BD09(百度坐标系)之间可以互相任意转换
$translate = new Translate(WGS84::class, GCJ02::class);// GPS坐标系转火星坐标系
$translate = new Translate(WGS84::class, BD09::class);// GPS坐标系转百度坐标系
$translate = new Translate(BD09::class, GCJ02::class);// 百度坐标系转火星坐标系
$translate = new Translate(BD09::class, WGS84::class);// 百度坐标系转GPS坐标系
$point     = $translate->translate($point);// 转换后坐标
```

## 坐标系转换：其他坐标格式

```php
$points = [
    ["东经98度2分43秒", "南纬24度45分16.1秒"],
    ["98°2′43.8″E", "24°45′16.1″S"],
    ["E98°2′43.8″", "S24°45′16.1″"],
    ["98°59′59.9″E", "24°59′59.9″N"],
    ["98°59′59.9″W", "24°59′59.9″S"],
    ["98.5°E", "24.5°S"],
    ["98.5E", "24.5S"],
    ["98.5", "-24.5"],
    [116.4028930664, 39.9034155951],
    [114.3347167969, 30.5433389542],
];

foreach ($points as $point) {
    $converter = new Converter($point[0], $point[1]);
}
// WGS84(GPS坐标系)/GCJ02(火星坐标系)/BD09(百度坐标系)之间可以互相任意转换
$translate = new Translate(WGS84::class, GCJ02::class);// GPS坐标系转火星坐标系
$translate = new Translate(WGS84::class, BD09::class);// GPS坐标系转百度坐标系
$translate = new Translate(BD09::class, GCJ02::class);// 百度坐标系转火星坐标系
$translate = new Translate(BD09::class, WGS84::class);// 百度坐标系转GPS坐标系
$point     = $translate->translate($converter);// 转换后坐标
```
