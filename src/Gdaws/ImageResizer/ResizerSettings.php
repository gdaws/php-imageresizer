<?php

namespace Gdaws\ImageResizer;

use Gdaws\ImageResizer\Mode\CropMode;
use Gdaws\ImageResizer\Mode\MaxMode;
use Gdaws\ImageResizer\Mode\StretchMode;

class ResizerSettings
{
    private $settings;
    
    public function __construct($settings = array())
    {
        $this->settings = $settings;
    }
    
    public function setting($name, $default = null)
    {
        return array_key_exists($name, $this->settings) ? 
            $this->settings[$name] : $default;
    }
    
    private function createMode($name)
    {
        switch($name)
        {
            case "crop":
                return new CropMode();
                break;
            
            case "max":
                return new MaxMode();
                break;
            
            case "stretch":
                return new StretchMode();
                break;
            
            default:
                
                if (class_exists($name)) {
                    
                    $reflection = new \ReflectionClass($name);
                    
                    $interface = "Gdaws\ImageResizer\Mode\ModeInterface";
                    
                    if ($reflection->implementsInterface($interface)) {
                        return $reflection->newInstance();
                    }
                    else{
                        throw \RuntimeException(
                            "Class '$name' does not implement interface '$interface'"
                        );
                    }
                }
                
                throw \RuntimeException("unknown mode");
        }
    }
    
    public function configure(Resizer $resizer)
    {
        $mode = $this->createMode($this->setting("mode", "max"));
        $mode_attributes = $this->settings;
        
        $output_attributes = $this->settings;
        
        $resizer->setMode($mode, $mode_attributes)
            ->setWidth($this->setting("width"))
            ->setHeight($this->setting("height"))
            ->setModeAttributes($mode_attributes)
            ->setOutputAttributes($output_attributes);
    }
}
