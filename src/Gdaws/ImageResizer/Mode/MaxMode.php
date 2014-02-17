<?php

namespace Gdaws\ImageResizer\Mode;

use Gdaws\ImageResizer\Mode\ModeInterface;
use Gdaws\ImageResizer\ImageInfo;
use Gdaws\ImageResizer\Editor\EditorInterface;

class MaxMode implements ModeInterface
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
        
        if ($src_width_per_height > 1) {
            
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
    }
}
