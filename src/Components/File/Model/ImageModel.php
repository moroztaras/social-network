<?php

namespace App\Components\File\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ImageModel
{
    /**
     * @Assert\Image(
     *     minWidth = 200,
     *     minHeight = 100,
     *     mimeTypes = {"image/jpeg", "image/png", "image/jpg"},
     *     mimeTypesMessage = "This is not a valid file format. Only JPEG or PNG allowed.",
     *     maxSize = "20Mi",
     *     maxSizeMessage = "Image file size exceeds 20MB."
     * )
     */
    public $image;
}
