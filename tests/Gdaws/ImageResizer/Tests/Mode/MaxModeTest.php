<?php

namespace Gdaws\ImageResizer\Mode;

use Gdaws\ImageResizer\Tests\TestCase;
use Gdaws\ImageResizer\Mode\MaxMode;

class MaxModeTest extends TestCase
{
    function testApplyEdits()
    {
        $editor = $this->getMock("Gdaws\ImageResizer\Editor\EditorInterface");
        
        $editor->expects($this->once())
            ->method("resize")
            ->with(
                $this->equalTo(15), 
                $this->equalTo(4), 
                $this->equalTo("bicubic_fixed")
            );
        
        $mode = new MaxMode();
        
        $mode->applyEdits(
            $this->createImageInfoMock(100, 25),
            15, 15, 
            array("interpolation" => "bicubic_fixed"), 
            $editor
        );
    }
}
