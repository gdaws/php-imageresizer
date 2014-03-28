<?php

namespace Gdaws\ImageResizer\Exception;

class ImageResizerModeException extends ImageResizerException
{
    function __construct($message)
    {
        parent::__construct($message);
    }
}
