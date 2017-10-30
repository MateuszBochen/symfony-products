<?php

namespace AppBundle\Services;

use AppBundle\Repository\ImageSizeRepository;

// using https://github.com/eventviva/php-image-resize
class ImageSaver
{
    private $imageSizeRepository;

    public function __construct(ImageSizeRepository $imageSizeRepository)
    {
        $this->imageSizeRepository = $imageSizeRepository;
    }
}
