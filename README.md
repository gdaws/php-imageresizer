Image Resizing Library
=============

High level image resizing library for PHP 5.3+

Usage example:
```php
use Gdaws\ImageResizer\ResizerFacade;
use Gdaws\ImageResizer\Exception\ImageResizerException;

$resizer = new ResizerFacade();

try {

    $resizer->resize('in.jpg', 'out.jpg', array(
        "mode" => "crop",
        "width" => 100,
        "height" => 100,
        "quality" => 80
    ));
}
catch(ImageResizerException $exception) {

    echo $exception->getMessage();
}

```

## Crop Mode

Scale and then crop the image.

## Max Mode

Scale the image to tightly fit the output dimensions while preserving the 
aspect ratio.

## Stretch Mode

Resize the image exactly to the dimensions specified.

## Common Settings

These are common settings that affect the output image:

* `width` of image in pixels. The value must be greater than 0. 
* `height` of image in pixels. The value must be greater than 0.
* `quality` sets the compression level. Accepts a value between 0 and 100.

It's possible to only specify one of the dimensions and let the resizer 
calculate the other dimension using the source image's aspect ratio.