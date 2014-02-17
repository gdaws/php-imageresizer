<?php

namespace Gdaws\ImageResizer\Editor;

use Gdaws\ImageResizer\ImageInfo;
use Gdaws\ImageResizer\Exception\ImageEditorException;

class GDEditor implements EditorInterface
{
    private $image;
    
    private $use_imagescale;
    private $use_imagecrop;
    
    function __construct($options = array())
    {
        $this->use_imagescale = array_key_exists("use_imagescale", $options) ?
            $options["use_imagescale"] : false;
        
        $this->use_imagecrop = array_key_exists("use_imagecrop", $options) ?
            $options["use_imagecrop"] : false;
    }
    
    public function loadFile(ImageInfo $image_info)
    {
        $filename = $image_info->getFilename();
        
        switch ($image_info->getType()) {
            
            case ImageInfo::TYPE_GIF:
            
                $image = @imagecreatefromgif($filename);
                
                break;
            
            case ImageInfo::TYPE_JPEG:
            
                $image = @imagecreatefromjpeg($filename);
                
                break;
            
            case ImageInfo::TYPE_PNG:
            
                $image = @imagecreatefrompng($filename);
                
                break;
            
            default:
                throw new ImageEditorException("image type unsupported");
        }
        
        if ($image === false) {
            throw new ImageEditorException("could not load image");
        }
        
        $this->image = $image;
    }
    
    private function destroy()
    {
        @imagedestroy($this->image);
        $this->image = null;
    }
    
    private function replace($new_image_resource)
    {
        @imagedestroy($this->image);
        $this->image = $new_image_resource;
    }
    
    private function error($message)
    {
        $this->destroy();
        
        throw new ImageEditorException($message);
    }
    
    public function resize($width, $height, $interpolation)
    {
        if (function_exists("imagescale") && $this->use_imagescale) {
            
            if (is_string($interpolation)) {
                switch ($interpolation) {
                    
                    case "bilinear_fixed":
                        $interpolation = IMG_BILINEAR_FIXED;
                        break;
                    
                    case "nearest_neighbour":
                        $interpolation = IMG_NEAREST_NEIGHBOUR;
                        break;
                    
                    case "bicubic":
                        $interpolation = IMG_BICUBIC;
                        break;
                    
                    case "bicubic_fixed":
                        $interpolation = IMG_BICUBIC_FIXED;
                        break;
                    
                    default:
                        $interpolation = null;
                }
            }
            
            if ($interpolation === null) {
                $interpolation = IMG_BILINEAR_FIXED;
            }
            
            $image = @imagescale($this->image, $width, $height, $interpolation);
            
            exit;
            
            if ($image === false) {
                $this->error("failed to resize image");
            }
        }
        else {
            
            $new_image = @imagecreatetruecolor($width, $height);
            
            if ($new_image === false) {
                $this->error("failed to create image");
            }
            
            $success = @imagecopyresampled(
                $new_image, 
                $this->image, 
                0, 0, 0, 0, 
                $width, $height, 
                imagesx($this->image), imagesy($this->image)
            );
            
            if ($success === false) {
                $this->error("failed to resize image");
            }
            
            $image = $new_image;
        }
        
        $this->replace($image);
    }
    
    public function crop($x, $y, $width, $height)
    {
        $width = min($width, imagesx($this->image) - $x);
        $height = min($height, imagesy($this->image) - $y);
        
        if (function_exists("imagecrop") && $this->use_imagecrop) {
            
            $image = @imagecrop($this->image, array(
                "x" => $x, 
                "y" => $y, 
                "width" => $width, 
                "height" => $height    
            ));
            
            if ($image === false) {
                $this->error("failed to crop image");
            }
        }
        else {
            
            $new_image = @imagecreatetruecolor($width, $height);
            
            if ($new_image === false) {
                $this->error("failed to create image");
            }
            
            $success = @imagecopy(
                $new_image,
                $this->image,
                0, 0,
                $x, $y,
                $width, $height
            );
            
            if ($success === false) {
                $this->error("failed to crop image"); 
            }
            
            $image = $new_image;
        }
        
        $this->replace($image);
    }
    
    public function saveFile($filename, $attributes)
    {
        
        if (array_key_exists("type", $attributes)) {
            $type = $attributes["type"];
        }
        else {
            
            $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
            
            if ($file_ext === "") {
                $this->error("no image type specified");
            }
            
            $types = array(
                "gif" => ImageInfo::TYPE_GIF,
                "jpg" => ImageInfo::TYPE_JPEG,
                "png" => ImageInfo::TYPE_PNG  
            );
            
            if (!array_key_exists($file_ext, $types)) {
                $this->error("unknown image type");
            }
            
            $type = $types[$file_ext];
        }
        
        switch ($type) {
            
            case ImageInfo::TYPE_GIF:
                
                $success = @imagegif($this->image, $filename);
                
                break;
            
            case ImageInfo::TYPE_JPEG:
            
                $quality = array_key_exists("quality", $attributes) ? 
                    intval($attributes["quality"]) : 75;
                
                $success = @imagejpeg($this->image, $filename, $quality);
                
                break;
                
            case ImageInfo::TYPE_PNG:
                
                $quality = array_key_exists("quality", $attributes) ? 
                    round(intval($attributes["quality"]) / 100 * 9) : 7;
                
                $success = @imagepng($this->image, $filename, $quality);
                
                break;
                
            default:
                $this->error("image type unsupported");
        }
        
        if ($success === false) {
            $this->error("failed to write image file");
        }
        
        $this->destroy();
    }
}
