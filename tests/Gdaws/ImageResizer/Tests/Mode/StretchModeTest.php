<?php

namespace Gdaws\ImageResizer\Mode;

use Gdaws\ImageResizer\Tests\TestCase;
use Gdaws\ImageResizer\Mode\StretchMode;

class StretchModeTest extends TestCase
{
    protected function getImageInfo($basename)
    {
        return new ImageInfo($this->getFixtureFilename($basename));
    }
    
    function testApplyEdits()
    {
        $editor = $this->getMock("Gdaws\ImageResizer\Editor\EditorInterface");
        
        $editor->expects($this->once())
            ->method("resize")
            ->with(
                $this->equalTo(100), 
                $this->equalTo(200), 
                $this->equalTo("bicubic_fixed")
            );
        
        $mode = new StretchMode();
        
        $mode->applyEdits(
            $this->createImageInfoMock(1, 1),
            100, 200, 
            array("interpolation" => "bicubic_fixed"), 
            $editor
        );
    }
}
