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
use ColorDifference\Test\ResourceLoader;

class ImageColorServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ResourceLoader
     */
    private $resourceLoader;

    /**
     * @var ImageColorsService
     */
    private $service;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->resourceLoader = new ResourceLoader();
    }

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

        printf(PHP_EOL."Select colors for %s step = %d time: %.5f sec, memory: %.2f KB".PHP_EOL, $path, $step, $time, $memory/1024);

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

        printf(PHP_EOL."Count colors for %s colors = %d step = %d, maxDiff = %f - time: %.5f sec, memory: %.2f KB".PHP_EOL,
            $path, count($colors), $step, $maxDiff, $time, $memory/1024);

        $this->assertEquals($expected, $result);
    }



    public function colorsFixture()
    {
        return [
            [$this->resourceLoader->getPath('1.jpg'), 10, 19532],
            [$this->resourceLoader->getPath('1.jpg'), 20, 6564],
            [$this->resourceLoader->getPath('1.jpg'), 30, 3174],
            [$this->resourceLoader->getPath('1.jpg'), 40, 1870],
            [$this->resourceLoader->getPath('1.jpg'), 60, 870],

            [$this->resourceLoader->getPath('2.jpg'), 10, 3438],
            [$this->resourceLoader->getPath('2.jpg'), 20, 930],
            [$this->resourceLoader->getPath('2.jpg'), 30, 430],
            [$this->resourceLoader->getPath('2.jpg'), 40, 251],
        ];
    }

    public function countColorsFixture()
    {
        return [
            [
                $this->resourceLoader->getPath('1.jpg'),
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
                $this->resourceLoader->getPath('1.jpg'),
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
            ],
            [
            $this->resourceLoader->getPath('2.jpg'),
                20,
                10,
                [
                    '#000000',
                    '#111111',
                    '#ffffff',

                ],
                [
                    '#000000' => 57,
                    '#111111' => 75,
                    '#ffffff' => 147,
                ]
            ],
            [
                $this->resourceLoader->getPath('2.jpg'),
                20,
                10,
                [
                    '#000000',
                    '#111111',
                    '#222222',
                    '#333333',
                    '#444444',
                    '#ffffff',

                ],
                [
                    '#000000' => 57,
                    '#111111' => 75,
                    '#ffffff' => 147,
                    '#222222' => 73,
                    '#333333' => 52,
                    '#444444' => 39,
                ]
            ],
            [
                $this->resourceLoader->getPath('2.jpg'),
                10,
                10,
                [
                    '#000000',
                    '#111111',
                    '#ffffff',
                ],
                [
                    '#000000' => 237,
                    '#111111' => 314,
                    '#ffffff' => 552,
                ]
            ],
            [
                $this->resourceLoader->getPath('2.jpg'),
                10,
                10,
                [
                    '#000000',
                    '#111111',
                    '#222222',
                    '#333333',
                    '#444444',
                    '#ffffff',

                ],
                [
                    '#000000' => 237,
                    '#111111' => 314,
                    '#ffffff' => 552,
                    '#222222' => 306,
                    '#333333' => 173,
                    '#444444' => 164,
                ]
            ],
            [
                $this->resourceLoader->getPath('2.jpg'),
                5,
                10,
                [
                    '#000000',
                    '#111111',
                    '#ffffff',
                ],
                [
                    '#000000' => 937,
                    '#111111' => 1270,
                    '#ffffff' => 2179,
                ]
            ],

        ];
    }

    private function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
} 