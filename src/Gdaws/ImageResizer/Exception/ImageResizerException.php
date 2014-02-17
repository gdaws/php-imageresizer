<?php

namespace Gdaws\ImageResizer\Exception;

class ImageResizerException extends \RuntimeException
{
    function __construct($message)
    {
        parent::__construct($message);
    }
}
