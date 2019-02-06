<?php

namespace App\Components\File\Twig;

use App\Components\File\FileAssistant;
use App\Components\File\ImageStyle;
use App\Entity\File;

class ImageTwig extends \Twig_Extension
{
    private $imageStyle;

    private $fileAssistant;

    public function __construct(ImageStyle $image_style, FileAssistant $fileAssistant)
    {
        $this->imageStyle = $image_style;
        $this->fileAssistant = $fileAssistant;
    }

    public function getFilters()
    {
        return [
      new \Twig_SimpleFilter('style_image', [$this, 'styleImage']),
    ];
    }

    public function styleImage(File $file, $style_name)
    {
        $url = $this->imageStyle->styleImage($file, $style_name);
        $webUrl = $this->fileAssistant->webDir($url);

        return $webUrl;
    }
}
