<?php
/**
 * Created by PhpStorm.
 * User: solarys
 * Date: 17.07.14
 * Time: 11:19
 */

namespace ColorDifference\Test;


class ResourceLoader
{

    private $resDir = '/../../res';

    public function getResourceDir()
    {
        return realpath(__DIR__.$this->resDir).'/';
    }

    public function load($filename)
    {
        return file_get_contents($this->getPath($filename));
    }

    public function getPath($filename)
    {
        return $this->getResourceDir().$filename;
    }
} 