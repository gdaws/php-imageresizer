<?php

namespace Gdaws\ImageResizer;

use Gdaws\ImageResizer\Exception\ImageFileException;

class ImageInfo
{
    const TYPE_GIF  = "gif";
    const TYPE_JPEG = "jpeg";
    const TYPE_PNG  = "png";
    
    private $filename;
    private $width;
    private $height;
    private $type;
    
    public function __construct($filename)
    {
        if (!file_exists($filename)) {
            throw new ImageFileException("file not found");
        }
        
        if (!is_readable($filename)) {
            throw new ImageFileException("permission denied to read file");
        }
        
        $info = @getimagesize($filename);
        
        if ($info === false) {
            throw new ImageFileException(
                "could not get image information from file"
            );
        }
        
        $this->filename = $filename;
        $this->width = $info[0];
        $this->height = $info[1];
        
        $types = array(
            IMAGETYPE_GIF  => self::TYPE_GIF,
            IMAGETYPE_JPEG => self::TYPE_JPEG,
            IMAGETYPE_PNG  => self::TYPE_PNG
        );
        
        if (!array_key_exists($info[2], $types)) {
            throw new ImageFileException(
                "unsupported image type"  
            );
        }
        
        $this->type = $types[$info[2]];
    }
    
    public function getFilename()
    {
        return $this->filename;
    }
    
    public function getWidth()
    {
        return $this->width;
    }
    
    public function getHeight()
    {
        return $this->height;   
    }
    
    public function getType()
    {
        return $this->type;
    }
}
