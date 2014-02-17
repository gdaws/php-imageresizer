<?php

namespace Gdaws\ImageResizer\Mode;

use Gdaws\ImageResizer\Mode\ModeInterface;
use Gdaws\ImageResizer\ImageInfo;
use Gdaws\ImageResizer\Editor\EditorInterface;
use Gdaws\ImageResizer\Exception\ImageResizerModeException;

class CropMode implements ModeInterface
{
    public function applyEdits(
        ImageInfo $image, $dst_width, $dst_height, $attributes, 
        EditorInterface $editor)
    {
        
        $interpolation = array_key_exists("interpolation", $attributes) ?
            $attributes["interpolation"] : null;
        
        $src_width = $image->getWidth();
        $src_height = $image->getHeight();
        
        $src_width_per_height = $src_width / $src_height;
        $src_height_per_width = $src_height / $src_width;
        
        $dst_width_per_height = $dst_width / $dst_height;
        
        $width_diff = abs($src_width - $dst_width);
        $height_diff = abs($src_height - $dst_height);
        
        if ($width_diff < $height_diff) {
            $resize_width = $dst_width;
            $resize_height = $src_height_per_width * $dst_width;
        }
        else {
            $resize_height = $dst_height;
            $resize_width = $src_width_per_height * $dst_height;    
        }
        
        $editor->resize(
            round($resize_width), 
            round($resize_height), 
            $interpolation
        );
        
        if($dst_width * $dst_height < $resize_width * $resize_height){
            
            $anchor = array_key_exists("anchor", $attributes) ? 
            $attributes["anchor"] : "center";
            
            $this->applyCrop(
                $resize_width, $resize_height, 
                $dst_width, $dst_height, 
                $anchor, $editor
            );
        }
    }
    
    private function applyCrop(
        $src_width, $src_height, $dst_width, $dst_height, $anchor, $editor)
    {
        
        $src_half_width = $src_width / 2;
        $src_half_height = $src_height / 2;
        
        $dst_half_width = $dst_width / 2;
        $dst_half_height = $dst_height / 2;
        
        $x_center = $src_half_width - $dst_half_width;
        $y_center = $src_half_height - $dst_half_height;
        
        $x_right = $src_width - $dst_width;
        $y_bottom = $src_height - $dst_height;
        
        switch ($anchor) {
            
            case "topleft":
                
                $x = 0;
                $y = 0;
                
                break;
            
            case "top":
            
                $x = $x_center;
                $y = 0;
                
                break;
            
            case "topright";
                
                $x = $x_right;
                $y = 0;
                
                break;
            
            case "right":
                
                $x = $x_right;
                $y = $y_center;
                
                break;
            
            case "bottomright":
                
                $x = $x_right;
                $y = $y_bottom;
                
                break;
            
            case "bottom":
            
                $x = $x_center;
                $y = $y_bottom;
            
                break;
            
            case "bottomleft":
            
                $x = 0;
                $y = $y_bottom;
                
                break;
            
            case "left":
            
                $x = 0;
                $y = $y_center;
                
                break;
            
            case "center":
            
                $x = $x_center;
                $y = $y_center;
                
                break;
            
            default:
                throw new ImageResizerModeException("unknown anchor value");
        }
        
        $editor->crop(
            round($x), 
            round($y), 
            round($dst_width), 
            round($dst_height)
        );
    }
}
