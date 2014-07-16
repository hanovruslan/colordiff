<?php
/**
 * Created by PhpStorm.
 * User: solarys
 * Date: 16.07.14
 * Time: 15:22
 */

namespace ColorDifference\Service;


use ColorDifference\CIE\CIEInterface;

class ColorService implements ColorServiceInterface
{

    /**
     * @var CIEInterface
     */
    private $cie;

    /**
     * @param \ColorDifference\CIE\CIEInterface $cie
     */
    public function setCie(CIEInterface $cie)
    {
        $this->cie = $cie;
    }

    /**
     * @return \ColorDifference\CIE\CIEInterface
     */
    public function getCie()
    {
        return $this->cie;
    }

    /**
     * @param $color1
     * @param $color2
     * @return float
     */
    public function getColorDifference($color1, $color2)
    {
        return $this->cie->getColorDifference($color1, $color2);
    }

    /**
     * @param $color1
     * @param $color2
     * @return float
     */
    public function getHexColorDifference($color1, $color2)
    {
        $color1 = $this->hex2rgb($color1);
        $color2 = $this->hex2rgb($color2);
        return $this->getColorDifference($color1, $color2);
    }

    /**
     * @param array $needle [r, g, b]
     * @param array $search
     * @param int $maxDiff
     * @return array
     */
    public function findColor($needle, $search, $maxDiff)
    {
        $result = [];
        foreach($search as $color){
            $diff = $this->cie->getColorDifference($needle, $color);
            if($diff <= $maxDiff){
                $result[] = $color;
            }
        }
        return $result;
    }

    /**
     * @param string $needle
     * @param array $search
     * @param int $maxDiff
     * @return array
     */
    public function findHexColor($needle, $search, $maxDiff)
    {
        $needle = $this->hex2rgb($needle);
        $colors = [];
        foreach($search as $color){
            $colors[] = $this->hex2rgb($color);
        }
        $rgbColors = $this->findColor($needle, $colors, $maxDiff);
        $result = [];
        foreach($rgbColors as $color){
            $result[] = $this->rgb2hex($color);
        }
        return $result;
    }

    /**
     * @param string $hex
     * @return array
     */
    public function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);
        if(strlen($hex) == 3){
            $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
        }else{
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return [$r, $g, $b];
    }

    /**
     * @param array $rgb
     * @return string
     */
    public function rgb2hex(array $rgb)
    {
        return sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);
    }


} 