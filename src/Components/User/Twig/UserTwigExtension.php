<?php

namespace App\Components\User\Twig;

use App\Components\File\FileAssistant;
use App\Components\File\ImageStyle;
use App\Entity\File;
use App\Entity\User;

class UserTwigExtension extends \Twig_Extension
{
    private $defaultImage = '/images/system/no-profile-pic.png';

    private $fileAssistant;

    private $imageStyle;

    public function __construct(FileAssistant $fileAssistant, ImageStyle $imageStyle)
    {
        $this->fileAssistant = $fileAssistant;
        $this->imageStyle = $imageStyle;
    }

    public function getFilters()
    {
        return [
      new \Twig_SimpleFilter('avatarStyleDefault', [$this, 'avatarStyleDefault']),
    ];
    }

    public function avatarStyleDefault(File $avatar = null)
    {
        $render = $this->defaultImage;

        if ($avatar instanceof File && $avatar->getId() > 0 && $this->fileAssistant->isFile($avatar)) {
            $fileRoot = $this->fileAssistant->rootUrl($avatar->getUrl());

            if ($this->fileAssistant->isImageMimeType($fileRoot)) {
                $imageStyle = $this->imageStyle->styleImage($avatar, '250_250_crop');
                $render = $this->fileAssistant->webDir($imageStyle);
            }
        }

        return $render;
    }

    public function getName()
    {
        return 'user';
    }
}
