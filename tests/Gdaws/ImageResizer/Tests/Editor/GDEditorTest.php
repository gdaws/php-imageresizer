<?php

namespace Gdaws\ImageResizer\Tests\Editor;

use Gdaws\ImageResizer\Tests\TestCase;
use Gdaws\ImageResizer\Editor\GDEditor;
use Gdaws\ImageResizer\ImageInfo;

class GDEditorTest extends TestCase
{
    private function loadEditor($basename)
    {
        $editor = new GDEditor;
        $editor->loadFile(new ImageInfo($this->getFixtureFilename($basename)));
        return $editor;
    }
    
    function testSaveType()
    {
        $dest = $this->getFixtureFilename("gd1_result.jpg");
        
        @unlink($dest);
        
        $editor = $this->loadEditor("gd1.png");
        $editor->saveFile($dest, array());
        
        $dest_info = new ImageInfo($dest);
        
        $this->assertEquals(ImageInfo::TYPE_JPEG, $dest_info->getType());
        
        @unlink($dest);
        
        $dest = $this->getFixtureFilename("gd1");
        
        @unlink($dest);
        
        $editor = $this->loadEditor("gd1.png");
        $editor->saveFile($dest, array("type" => ImageInfo::TYPE_JPEG));
        
        $dest_info = new ImageInfo($dest);
        
        $this->assertEquals(ImageInfo::TYPE_JPEG, $dest_info->getType());
        
        @unlink($dest);
    }
    
    function testResize()
    {
        $editor = $this->loadEditor("gd1.png");
        
        $editor->resize(5, 10, null);
        
        $dest = $this->getFixtureFilename("gd1_result.png");
        
        @unlink($dest);
        
        $editor->saveFile($dest, array());
        
        $dest_info = new ImageInfo($dest);
        
        $this->assertEquals(5, $dest_info->getWidth());
        $this->assertEquals(10, $dest_info->getHeight());
        
        @unlink($dest);      
    }
    
    function testCrop()
    {
        $editor = $this->loadEditor("gd1.png");
        
        $editor->crop(1, 2, 5, 10);
        
        $dest = $this->getFixtureFilename("gd1_result.png");
        
        @unlink($dest);
        
        $editor->saveFile($dest, array());
        
        $dest_info = new ImageInfo($dest);
        
        $this->assertEquals(5, $dest_info->getWidth());
        $this->assertEquals(10, $dest_info->getHeight());
        
        @unlink($dest);
    }
}
