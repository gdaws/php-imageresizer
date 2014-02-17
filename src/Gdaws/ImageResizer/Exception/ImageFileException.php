<?php

namespace Gdaws\ImageResizer\Exception;

class ImageFileException extends ImageResizerException
{
    function __construct($message)
    {
        parent::__construct($message);
    }
}
