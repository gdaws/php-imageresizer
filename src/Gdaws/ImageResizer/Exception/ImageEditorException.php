<?php

namespace Gdaws\ImageResizer\Exception;

class ImageEditorException extends ImageResizerException
{
    function __construct($message)
    {
        parent::__construct($message);
    }
}
