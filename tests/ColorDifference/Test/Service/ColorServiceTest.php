<?php
/**
 * Created by PhpStorm.
 * User: solarys
 * Date: 16.07.14
 * Time: 15:26
 */

namespace ColorDifference\Test\Service;


use ColorDifference\CIE\CIE76;
use ColorDifference\CIE\CIEInterface;
use ColorDifference\Service\ColorService;
use ColorDifference\Service\ColorServiceInterface;

class ColorServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ColorService
     */
    private $service;

    /**
     * @var CIEInterface
     */
    private $cie;


    public function setUp()
    {
        $this->service = new ColorService();
        $this->cie = $this->getMock('ColorDifference\CIE\CIEInterface');
        $this->service->setCie($this->cie);
    }

    /**
     * @dataProvider differenceFixture
     * @param $color1
     * @param $color2
     * @param $isHex
     * @param $expected
     */
    public function testGetColorDifference($color1, $color2, $isHex, $expected)
    {
        if($isHex){
            $rgb1 = $this->service->hex2rgb($color1);
            $rgb2 = $this->service->hex2rgb($color2);
        }else{
            $rgb1 = $color1;
            $rgb2 = $color2;
        }
        $this->cie->expects($this->once())
            ->method('getColorDifference')
            ->with($rgb1, $rgb2)
            ->will($this->returnValue($expected));

        $result = $this->service->getColorDifference($color1, $color2, $isHex);
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider findFixture
     * @param $needle
     * @param $colors
     * @param $diffs
     * @param $maxDiff
     * @param $expected
     */
    public function testFindColor($needle, $colors, $diffs, $maxDiff, $expected)
    {

        foreach($colors as $key => $color){
            $this->cie->expects($this->at($key))
                ->method('getColorDifference')
                ->with($needle, $color)
                ->will($this->returnValue($diffs[$key]));
        }
        $result = $this->service->findColor($needle, $colors, $maxDiff);
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider hex2rgbFixture
     */
    public function testHex2rgb($hex, $expected)
    {
        $result = $this->service->hex2rgb($hex);
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider rgb2hexFixture
     */
    public function testRgb2hex($expected, $rgb)
    {
        $result = $this->service->rgb2hex($rgb);
        $this->assertEquals($expected, $result);
    }

    public function hex2rgbFixture()
    {
        return [
            ['#000000', [0, 0, 0]],
            ['#000', [0, 0, 0]],
            ['#010101', [1, 1, 1]],
            ['#111', [17, 17, 17]],
            ['#0a0a0a', [10, 10, 10]],
            ['#aaa', [170, 170, 170]],
            ['#ffffff', [255, 255, 255]],
            ['#fff', [255, 255, 255]],
        ];
    }

    public function rgb2hexFixture()
    {
        return [
            ['#000000', [0, 0, 0]],
            ['#010101', [1, 1, 1]],
            ['#0a0a0a', [10, 10, 10]],
            ['#ffffff', [255, 255, 255]],
        ];
    }

    public function differenceFixture()
    {
        return [
            [[255, 255, 255], [0, 0, 0], false, 100],
            [[255, 0, 0], [0, 255, 0], false, 170.58],
            [[255, 0, 0], [0, 0, 255], false, 176.33],
            [[0, 255, 0], [0, 0, 255], false, 258.69],
            [[0, 0, 0], [255, 255, 255], false, 100],
            [[254, 254, 254], [255, 255, 255], false, 0.35],
            [[254, 255, 255], [255, 255, 255], false, 0.36],
            [[254, 254, 255], [255, 255, 255], false, 0.6],
            [[1, 1, 1], [0, 0, 0], false, 0.27],
        ];
    }

    public function findFixture()
    {
        return [
            [
                [255, 255, 255],
                [
                    [255, 255, 255],
                    [254, 254, 254],
                ],
                [0, 1],
                0,
                [
                    [255, 255, 255]
                ]
            ],
            [
                [255, 255, 255],
                [
                    [255, 255, 255],
                    [254, 254, 254],
                    [253, 253, 253],
                ],
                [0, 1, 2],
                1,
                [
                    [255, 255, 255],
                    [254, 254, 254],
                ]
            ],
        ];
    }
} 