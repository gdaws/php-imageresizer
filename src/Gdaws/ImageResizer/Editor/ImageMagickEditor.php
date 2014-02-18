<?php

namespace Gdaws\ImageResizer\Editor;

use Gdaws\ImageResizer\ImageInfo;
use Gdaws\ImageResizer\Exception\ImageEditorException;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

class ImageMagickEditor implements EditorInterface
{
    private $program_path;
    
    private $process_timeout;
    
    private $source;
    private $resize;
    private $crop;
    
    public function __construct($program_path = "convert")
    {
        $this->program_path = $program_path;
        
        $this->process_timeout = null;
        
        $this->source = null;
        $this->resize = null;
        $this->crop = null;
    }
    
    public static function newInstanceIfSupported()
    {
        if (defined("PHP_WINDOWS_VERSION_BUILD")) {
            
            $commands = array(
                "convert.exe",
                "C:\\Program Files\\ImageMagick-6.8.8-Q16\\convert.exe"
            );
        }
        else {
            
            $commands = array(
                "convert",
                "/usr/bin/convert",
                "/usr/local/convert"
            );
        }
        
        foreach ($commands as $command) {
            
            if (self::testCommand($command . " --version", "ImageMagick")) {
                return new self($command);
            }
        }
    }
    
    private static function testCommand($cmd, $contains_output)
    {
        $process = new Process($cmd);
        $exit_status = $process->run();
        
        return $exit_status === 0 && 
            strpos($process->getOutput(), $contains_output) !== false;
    }
    
    public function setProcessTimeout($timeout)
    {
        $this->process_timeout = $timeout;
    }
    
    public function loadFile(ImageInfo $source)
    {
        $this->source = $source;
    }
    
    public function resize($width, $height, $interpolation)
    {
        $this->resize = array(
            "width" => $width,
            "height" => $height,
            "interpolation" => $interpolation    
        );
    }
    
    public function crop($x, $y, $width, $height)
    {
        $this->crop = array(
            "x" => $x,
            "y" => $y,
            "width" => $width,
            "height" => $height  
        );
    }
    
    public function saveFile($destination, $attributes)
    {
        $args = array(
            $this->source->getFilename()
        );
        
        if ($this->resize !== null) {
           $args[] = "-resize";
           $args[] = $this->resize["width"] . "x" . $this->resize["height"] 
           ."!";
        }
        
        if ($this->crop !== null) {
            $args[] = "-crop";
            $args[] = $this->crop["width"] . "x" . $this->crop["height"] . 
                "+" . $this->crop["x"] . "+" . $this->crop["y"];
        }
        
        if (array_key_exists("quality", $attributes)) {
            $args[] = "-quality";
            $args[] = $attributes["quality"];    
        }
        
        $forced_output_type = "";
        
        if (array_key_exists("type", $attributes)) {
            switch ($attributes["type"]) {
                case ImageInfo::TYPE_PNG:
                    $forced_output_type = "png:";
                    break;
                case ImageInfo::TYPE_GIF:
                    $forced_output_type = "gif:";
                    break;
                case ImageInfo::TYPE_JPEG:
                    $forced_output_type = "jpg:";
                    break;
            }
        }
        
        $args[] = $forced_output_type . $destination;
        
        $builder = new ProcessBuilder();
        
        $builder->setPrefix($this->program_path);
        $builder->setArguments($args);
        
        $process = $builder->getProcess();
        
        $process->setTimeout($this->process_timeout);
        
        $exit_status = $process->run();
        
        if ($exit_status !== 0) {
            throw New ImageEditorException(
                "Command failed with output: " . $process->getErrorOutput()
            );
        }
    }
}
