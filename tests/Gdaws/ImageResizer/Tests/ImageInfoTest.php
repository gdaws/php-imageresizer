<?php

namespace Gdaws\ImageResizer\Tests;

use Gdaws\ImageResizer\Tests\TestCase;
use Gdaws\ImageResizer\ImageInfo;
use Gdaws\ImageResizer\Exception\ImageFileException;

class ImageInfoTest extends TestCase
{
    protected function create($basename)
    {
        return new ImageInfo($this->getFixtureFilename($basename));
    }
    
    /**
     * @expectedException        Gdaws\ImageResizer\Exception\ImageFileException
     * @expectedExceptionMessage file not found
     */
    function testFileMissing()
    {
        $this->create("aweoriua");
    }
    
    /**
     * @expectedException        Gdaws\ImageResizer\Exception\ImageFileException
     * @expectedExceptionMessage could not get image information from file
     */
    function testInvalidImage()
    {
        $this->create("notimage.txt");
    }
    
    /**
     * @expectedException        Gdaws\ImageResizer\Exception\ImageFileException
     * @expectedExceptionMessage unsupported image type
     */
    function testUnsupportedImage()
    {
        $this->create("2.tiff");
    }
    
    function testGetFilename()
    {
        $info = $this->create("1.jpg");
        
        $this->assertEquals($this->getFixtureFilename("1.jpg"), $info->getFilename());
    }
    
    function testGetWidth()
    {
        $info = $this->create("1.gif");
        
        $this->assertEquals(1, $info->getWidth());
    }
    
    function testGetHeight()
    {
        $info = $this->create("2.png");
        
        $this->assertEquals(2, $info->getHeight());
    }
    
    function testGetType()
    {
        $gif = $this->create("1.gif");
        $this->assertEquals(ImageInfo::TYPE_GIF, $gif->getType());
        
        $jpg = $this->create("1.jpg");
        $this->assertEquals(ImageInfo::TYPE_JPEG, $jpg->getType());
        
        $png = $this->create("1.png");
        $this->assertEquals(ImageInfo::TYPE_PNG, $png->getType());
    }
}
