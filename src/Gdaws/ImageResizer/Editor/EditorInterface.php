<?php

namespace Gdaws\ImageResizer\Editor;

use Gdaws\ImageResizer\ImageInfo;

interface EditorInterface
{
    public function loadFile(ImageInfo $image_info);
    
    public function resize($width, $height, $interpolation);
    
    public function crop($x, $y, $width, $height);
    
    public function saveFile($filename, $attributes);
}
