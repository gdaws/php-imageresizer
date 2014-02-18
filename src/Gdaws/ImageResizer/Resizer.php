<?php

namespace Gdaws\ImageResizer;

use Gdaws\ImageResizer\Editor\EditorInterface;
use Gdaws\ImageResizer\Mode\ModeInterface;
use Gdaws\ImageResizer\Exception\ImageResizerException;

class Resizer
{
    private $editor;
    private $mode;
    
    private $width;
    private $height;
    
    private $upsize;
    
    private $mode_attributes;
    private $output_attributes;
    
    public function __construct(EditorInterface $editor) 
    {
        $this->editor = $editor;
        
        $this->mode = null;
        $this->mode_attributes = array();
        
        $this->width = null;
        $this->height = null;
        
        $this->upsize = false;
        
        $this->interpolation = null;
        
        $this->output_attributes = array();
    }
    
    public function setMode(ModeInterface $mode, $attributes = array())
    {
        $this->mode = $mode;
        $this->mode_attributes = $attributes;
        return $this;
    }
    
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }
    
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }
    
    public function setQuality($quality)
    {
        $this->output_attributes["quality"] = $quality;
        return $this;
    }
    
    public function setModeAttributes($attributes)
    {
        $this->mode_attributes = $attributes;
        return $this;
    }
    
    public function setOutputAttributes($attributes)
    {
        $this->output_attributes = $attributes;
        return $this;
    }
    
    public function setInterpolation($interpolation)
    {
        $this->mode_attributes["interpolation"] = $interpolation;
        return $this;
    }
    
    public function setUpsize($enable)
    {
        $this->upsize = $enable;
        return $this;
    }
    
    public function resize($source, $destination)
    {
        $source_info = $source instanceof ImageInfo ? 
            $source : new ImageInfo($source);
        
        $src_width = $source_info->getWidth();
        $src_height = $source_info->getHeight();
        
        $dst_width = $this->width;
        $dst_height = $this->height;
        
        if ($dst_width === null && $dst_height === null) {
            
            throw new ImageResizerException(
                "no output dimensions specified"
            );
        }
        
        $src_is_smaller = $src_width < $dst_width && $src_height < $dst_height;
        
        if ($src_is_smaller && !$this->upsize) {
            
            $this->editor->loadFile($source);
            $this->editor->saveFile($destination, $this->output_attributes);
            
            return $this;
        }
        
        if ($dst_width === null) {
            $dst_width = $src_width / $src_height * $dst_height;
        }
        else if ($dst_height === null) {
            $dst_height = $src_height / $src_width * $dst_width;
        }
        
        $this->editor->loadFile($source_info);
        
        $this->mode->applyEdits(
            $source_info, 
            $dst_width, 
            $dst_height, 
            $this->mode_attributes, 
            $this->editor
        );
        
        $this->editor->saveFile($destination, $this->output_attributes);
        
        return $this;
    }
}
