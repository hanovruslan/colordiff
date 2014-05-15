<?php

namespace ColorDifference\Test\CIE;

use ColorDifference\CIE\CIE76;


class CIE76Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider fixture
     * @param type $rgb1
     * @param type $rgb2
     * @param type $expected
     */
    public function testGetColorDifference($rgb1, $rgb2, $expected)
    {
        $cie76 = new CIE76();
        $result = $cie76->getColorDifference($rgb1, $rgb2);
        $this->assertEquals($expected, round($result, 2));
    }
    
    public function fixture()
    {
        return [
            [[255, 255, 255], [0, 0, 0], 100],
            [[255, 0, 0], [0, 255, 0], 170.58],
            [[255, 0, 0], [0, 0, 255], 176.33],
            [[0, 255, 0], [0, 0, 255], 258.69],
            [[0, 0, 0], [255, 255, 255], 100],
            [[254, 254, 254], [255, 255, 255], 0.35],
            [[254, 255, 255], [255, 255, 255], 0.36],
            [[254, 254, 255], [255, 255, 255], 0.6],
            [[1, 1, 1], [0, 0, 0], 0.27],
        ];
    }
}