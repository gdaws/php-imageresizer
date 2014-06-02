<?php

namespace Gdaws\ImageResizer\Mode;

use Gdaws\ImageResizer\Tests\TestCase;
use Gdaws\ImageResizer\Mode\CropMode;

class CropModeTest extends TestCase
{
    
    protected function createEditor(
        $resize_width, $resize_height,
        $crop_x = null, $crop_y = null, 
        $crop_width = null, $crop_height = null, 
        $resize_interpolation = null)
    {
        
        $editor = $this->getMock("Gdaws\ImageResizer\Editor\EditorInterface");
        
        $editor->expects($this->once())
            ->method("resize")
            ->with(
                $this->equalTo($resize_width), 
                $this->equalTo($resize_height), 
                $this->equalTo($resize_interpolation)
            );
        
        $has_crop = $crop_x !== null && $crop_y !== null && 
            $crop_width !== null && $crop_height !== null;
        
        if ($has_crop) {
            
            $editor->expects($this->once())
            ->method("crop")
            ->with(
                $this->equalTo($crop_x),
                $this->equalTo($crop_y),
                $this->equalTo($crop_width),
                $this->equalTo($crop_height)
            );
        }
        
        return $editor;
    }
    
    function testApplyEdits()
    {
        $editor = $this->createEditor(280, 70, 105, 0, 70, 70);
        
        $mode = new CropMode();
        
        $mode->applyEdits(
            $this->createImageInfoMock(400, 100), 70, 70, array(), $editor
        );
        
        $editor = $this->createEditor(90, 360, 0, 130, 90, 100);
        
        $mode->applyEdits(
            $this->createImageInfoMock(100, 400), 90, 100, array(), $editor
        );
        
        $editor = $this->createEditor(225, 172, 0, 41, 225, 90);
        
        $mode->applyEdits(
            $this->createImageInfoMock(2000, 1530), 225, 90, array(), $editor
        );
    }
    
    function testNoCrop()
    {
        $editor = $this->createEditor(100, 100);
        
        $mode = new CropMode();
        
        $mode->applyEdits(
            $this->createImageInfoMock(100, 100), 100, 100, array(), $editor
        );
        
        $editor = $this->createEditor(50, 50);
        
        $mode->applyEdits(
            $this->createImageInfoMock(100, 100), 50, 50, array(), $editor
        );
    }
    
    function testUpsize()
    {
        $editor = $this->createEditor(2000, 500, 750, 0, 500, 500);
        
        $mode = new CropMode();
        
        $mode->applyEdits(
            $this->createImageInfoMock(400, 100), 500, 500, array(), $editor
        );
    }
    
    function testTopLeftAnchor()
    {
        $editor = $this->createEditor(280, 70, 0, 0, 70, 70);
        
        $mode = new CropMode();
        
        $mode->applyEdits(
            $this->createImageInfoMock(400, 100), 70, 70, 
            array(
                "anchor" => "topleft"
            ), $editor
        );
    }
}
