<?php

namespace Gdaws\ImageResizer\Tests;

use Gdaws\ImageResizer\Tests\TestCase;
use Gdaws\ImageResizer\Resizer;

class ResizerTest extends TestCase
{
    /**
     * @expectedException        Gdaws\ImageResizer\Exception\ImageResizerException
     * @expectedExceptionMessage no output dimensions specified
     */
    function testNoDimensionsSpecified()
    {
        $source = $this->createImageInfoMock(200, 400, "test.jpg");
        
        $editor = $this->getMock("Gdaws\ImageResizer\Editor\EditorInterface");
        $mode = $this->getMock("Gdaws\ImageResizer\Mode\ModeInterface");
        
        $resizer = new Resizer($editor);
        
        $resizer->setMode($mode);
        $resizer->resize($source, "output.jpg");
    }
    
    function testFullUsage()
    {
        $source = $this->createImageInfoMock(200, 200, "test.jpg");
        
        $editor = $this->getMock("Gdaws\ImageResizer\Editor\EditorInterface");
        
        $editor->expects($this->at(0))
            ->method("loadFile")
            ->with($this->equalTo($source));
        
        $editor->expects($this->at(1))
            ->method("saveFile")
            ->with(
                $this->equalTo("output.jpg"),
                $this->equalTo(array("quality" => 90))
            );
        
        $resizer = new Resizer($editor);
        
        $mode = $this->getMock("Gdaws\ImageResizer\Mode\ModeInterface");
        
        $mode->expects($this->once())
            ->method("applyEdits")
            ->with(
                $this->equalTo($source), 
                $this->equalTo(100), 
                $this->equalTo(200),
                $this->equalTo(array("interpolation" => "bicubic"))
            );
        
        $resizer->setMode($mode)
            ->setWidth(100)
            ->setHeight(200)
            ->setQuality(90)
            ->setInterpolation("bicubic");
        
        $resizer->resize($source, "output.jpg");
    }
    
    function testNoHeight()
    {
        $source = $this->createImageInfoMock(200, 400, "test.jpg");
        
        $editor = $this->getMock("Gdaws\ImageResizer\Editor\EditorInterface");
        
        $editor->expects($this->at(0))
            ->method("loadFile")
            ->with($this->equalTo($source));
        
        $editor->expects($this->at(1))
            ->method("saveFile")
            ->with(
                $this->equalTo("output.jpg"),
                $this->equalTo(array())
            );
        
        $resizer = new Resizer($editor);
        
        $mode = $this->getMock("Gdaws\ImageResizer\Mode\ModeInterface");
        
        $mode->expects($this->once())
            ->method("applyEdits")
            ->with(
                $this->equalTo($source), 
                $this->equalTo(100), 
                $this->equalTo(200),
                $this->equalTo(array())
            );
        
        $resizer->setMode($mode)
            ->setWidth(100);
        
        $resizer->resize($source, "output.jpg");
    }
    
    function testNoWidth()
    {
        $source = $this->createImageInfoMock(200, 400, "test.jpg");
        
        $editor = $this->getMock("Gdaws\ImageResizer\Editor\EditorInterface");
        
        $editor->expects($this->at(0))
            ->method("loadFile")
            ->with($this->equalTo($source));
        
        $editor->expects($this->at(1))
            ->method("saveFile")
            ->with(
                $this->equalTo("output.jpg"),
                $this->equalTo(array())
            );
        
        $resizer = new Resizer($editor);
        
        $mode = $this->getMock("Gdaws\ImageResizer\Mode\ModeInterface");
        
        $mode->expects($this->once())
            ->method("applyEdits")
            ->with(
                $this->equalTo($source), 
                $this->equalTo(50), 
                $this->equalTo(100),
                $this->equalTo(array())
            );
        
        $resizer->setMode($mode)
            ->setHeight(100);
        
        $resizer->resize($source, "output.jpg");
    }
    
    function testNoUpsize()
    {
        $source = $this->createImageInfoMock(200, 400, "test.jpg");
        
        $editor = $this->getMock("Gdaws\ImageResizer\Editor\EditorInterface");
        
        $editor->expects($this->at(0))
            ->method("loadFile")
            ->with($this->equalTo($source));
        
        $editor->expects($this->at(1))
            ->method("saveFile")
            ->with(
                $this->equalTo("output.jpg"),
                $this->equalTo(array())
            );
        
        $resizer = new Resizer($editor);
        
        $mode = $this->getMock("Gdaws\ImageResizer\Mode\ModeInterface");
        
        $mode->expects($this->any())
            ->method("applyEdits")
            ->will($this->throwException(new \Exception("expected no call")));
        
        $resizer->setMode($mode)
            ->setWidth(800)
            ->setHeight(600);
        
        $resizer->resize($source, "output.jpg");
    }
    
    function testUpsize()
    {
        $source = $this->createImageInfoMock(200, 400, "test.jpg");
        
        $editor = $this->getMock("Gdaws\ImageResizer\Editor\EditorInterface");
        
        $editor->expects($this->at(0))
            ->method("loadFile")
            ->with($this->equalTo($source));
        
        $editor->expects($this->at(1))
            ->method("saveFile")
            ->with(
                $this->equalTo("output.jpg"),
                $this->equalTo(array())
            );
        
        $resizer = new Resizer($editor);
        
        $mode = $this->getMock("Gdaws\ImageResizer\Mode\ModeInterface");
        
        $mode->expects($this->once())
            ->method("applyEdits")
            ->with(
                $this->equalTo($source), 
                $this->equalTo(800), 
                $this->equalTo(800),
                $this->equalTo(array())
            );
        
        $resizer->setMode($mode)
            ->setWidth(800)
            ->setHeight(800)
            ->setUpsize(true);
        
        $resizer->resize($source, "output.jpg");
    }
}
