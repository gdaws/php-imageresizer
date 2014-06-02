<?php

namespace Gdaws\ImageResizer\Tests;

use Gdaws\ImageResizer\ImageInfo;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function getFixtureFilename($basename)
    {
        return __DIR__ . "/fixtures/" . $basename;
    }
    
    protected function createImageInfo($basename)
    {
        return new ImageInfo($this->getFixtureFilename($basename));   
    }
    
    protected function createImageInfoMock($width, $height, $filename = null)
    {
        $stub = $this->getMockBuilder("Gdaws\ImageResizer\ImageInfo")
            ->disableOriginalConstructor()
            ->getMock();
        
        $stub->expects($this->any())
            ->method("getWidth")
            ->will($this->returnValue($width));
        
        $stub->expects($this->any())
            ->method("getHeight")
            ->will($this->returnValue($height));
        
        if ($filename) {
            $stub->expects($this->any())
                ->method("getFilename")
                ->will($this->returnValue($filename));
        }
        
        return $stub;
    }
}
