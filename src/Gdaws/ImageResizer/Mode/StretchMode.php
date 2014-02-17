<?php

namespace Gdaws\ImageResizer\Mode;

use Gdaws\ImageResizer\Mode\ModeInterface;
use Gdaws\ImageResizer\ImageInfo;
use Gdaws\ImageResizer\Editor\EditorInterface;

class StretchMode implements ModeInterface
{
    public function applyEdits(
        ImageInfo $image, $dst_width, $dst_height, $attributes, 
        EditorInterface $editor)
    {
        
        $interpolation = array_key_exists("interpolation", $attributes) ?
            $attributes["interpolation"] : null;
        
        $editor->resize($dst_width, $dst_height, $interpolation);
    }
}
