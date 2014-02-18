<?php

namespace Gdaws\ImageResizer;

use Gdaws\ImageResizer\Editor\EditorInterface;
use Gdaws\ImageResizer\Editor\ImageMagickEditor;
use Gdaws\ImageResizer\Editor\GDEditor;
use Gdaws\ImageResizer\ResizerSettings;
use Gdaws\ImageResizer\Resizer;

class ResizerFacade
{
    private $editor;
    
    public function __construct(EditorInterface $editor = null)
    {
        
        if ($editor === null) {
            
            $editor = ImageMagickEditor::newInstanceIfSupported();
            
            if ($editor === null) {
                
                $editor = GDEditor::newInstanceIfSupported();
                
                if ($editor === null) {
                    
                    throw new \RuntimeException(
                        "failed to detect image processing support"
                    );
                }
            }
        }
        
        $this->editor = $editor;
    }
    
    public function getEditor()
    {
        return $this->editor;
    }
    
    public function resize($source, $destination, array $settings)
    {    
        $resizer = new Resizer($this->editor);
        
        $settings = new ResizerSettings($settings);
        $settings->configure($resizer);
        
        return $resizer->resize($source, $destination);
    }
}
