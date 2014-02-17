<?php

namespace Gdaws\ImageResizer\Mode;

use Gdaws\ImageResizer\ImageInfo;
use Gdaws\ImageResizer\Editor\EditorInterface;

interface ModeInterface
{
    public function applyEdits(
        ImageInfo $image, $width, $height, $attributes, 
        EditorInterface $editor
    );
}
