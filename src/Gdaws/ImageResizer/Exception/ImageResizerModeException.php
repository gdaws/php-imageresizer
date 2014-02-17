<?php

namespace Gdaws\ImageResizer\Exception;

class ImageResizerModeException extends \RuntimeException
{
    function __construct($message)
    {
        parent::__construct($message);
    }
}
