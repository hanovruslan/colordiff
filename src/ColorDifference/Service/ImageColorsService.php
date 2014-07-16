<?php
/**
 * Created by PhpStorm.
 * User: solarys
 * Date: 16.07.14
 * Time: 12:09
 */

namespace ColorDifference\Service;


use ColorDifference\CIE\CIEInterface;

class ImageColorsService
{

    /**
     * @var ColorServiceInterface
     */
    private $colorService;

    /**
     * @param ColorServiceInterface $colorService
     */
    public function setColorService(ColorServiceInterface $colorService)
    {
        $this->colorService = $colorService;
    }

    /**
     * @return ColorServiceInterface
     */
    public function getColorService()
    {
        return $this->colorService;
    }




    /**
     * @param string $imagePath
     * @param int $step
     * @return array
     */
    public function getColors($imagePath, $step = 50)
    {
        $result = [];
        $image = new \Imagick();
        $image->readImage($imagePath);
        for($y = 0; $y < $image->getimageheight(); $y += $step){
            for($x = 0; $x < $image->getimagewidth(); $x += $step){
                $rgbPixel = $image->getimagepixelcolor($x, $y)->getcolor();
                $key = $this->getColorService()->rgb2hex([$rgbPixel['r'], $rgbPixel['g'], $rgbPixel['b']]);
                if(!array_key_exists($key, $result)){
                    $result[$key]  = 0;
                }
                $result[$key]++ ;
            }
        }
        return $result;
    }

    public function countColors($path, $colors, $step = 50, $maxDiff = 10)
    {
        $imageColors = $this->getColors($path, $step);
        $result = [];
        foreach($colors as $color){
            $result[$color] = 0;
            $colorsFound = $this->getColorService()->findHexColor($color, array_keys($imageColors), $maxDiff);
            foreach($colorsFound as $hexColor){
                $result[$color] += $imageColors[$hexColor];
            }
        }
        return $result;
    }


} 