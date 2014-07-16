<?php
/**
 * Created by PhpStorm.
 * User: solarys
 * Date: 16.07.14
 * Time: 12:14
 */

namespace ColorDifference\Test\Service;



use ColorDifference\CIE\CIE76;
use ColorDifference\Service\ColorService;
use ColorDifference\Service\ImageColorsService;

class ImageColorServiceTest extends \PHPUnit_Framework_TestCase
{

    private $resDir = '/../../../res/';

    /**
     * @var ImageColorsService
     */
    private $service;

    public function setUp()
    {
        $this->service = new ImageColorsService();
        $colorService = new ColorService();
        $colorService->setCie(new CIE76());
        $this->service->setColorService($colorService);

    }

    /**
     * @dataProvider colorsFixture
     */
    public function testGetColors($path, $step, $expected)
    {
        $startTime = $this->microtime_float();
        $startMemory = memory_get_usage();

        $colors = $this->service->getColors($path, $step);

        $time = $this->microtime_float() - $startTime;
        $memory = memory_get_usage() - $startMemory;

        printf(PHP_EOL."Select colors for step = %d time: %.5f sec, memory: %.2f KB".PHP_EOL, $step, $time, $memory/1024);

        $this->assertEquals($expected, count($colors));
    }

    /**
     * @dataProvider countColorsFixture
     */
    public function testCountColors($path, $step, $maxDiff, $colors, $expected)
    {
        $startTime = $this->microtime_float();
        $startMemory = memory_get_usage();

        $result = $this->service->countColors($path, $colors, $step, $maxDiff);

        $time = $this->microtime_float() - $startTime;
        $memory = memory_get_usage() - $startMemory;

        printf(PHP_EOL."Count colors for step = %d, maxDiff = %f - time: %.5f sec, memory: %.2f KB".PHP_EOL,
            $step, $maxDiff, $time, $memory/1024);

        $this->assertEquals($expected, $result);
    }



    public function colorsFixture()
    {
        return [
            [$this->getResDir().'1.jpg', 10, 19532],
            [$this->getResDir().'1.jpg', 20, 6564],
            [$this->getResDir().'1.jpg', 30, 3174],
            [$this->getResDir().'1.jpg', 40, 1870],
        ];
    }

    public function countColorsFixture()
    {
        return [
            [
                $this->getResDir().'1.jpg',
                20,
                10,
                [
                    '#000000',
                    '#111111',
                    '#ffffff',
                ],
                [
                    '#000000' => 162,
                    '#111111' => 336,
                    '#ffffff' => 988,
                ]
            ],
            [
                $this->getResDir().'1.jpg',
                10,
                5,
                [
                    '#000000',
                    '#111111',
                    '#ffffff',
                ],
                [
                    '#000000' => 239,
                    '#111111' => 451,
                    '#ffffff' => 391,
                ]
            ],
            [
                $this->getResDir().'1.jpg',
                10,
                10,
                [
                    '#000000',
                    '#111111',
                    '#ffffff',
                ],
                [
                    '#000000' => 621,
                    '#111111' => 1352,
                    '#ffffff' => 3970,
                ]
            ]
        ];
    }

    private function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    public function getResDir()
    {
        return realpath(__DIR__.$this->resDir).'/';
    }


} 