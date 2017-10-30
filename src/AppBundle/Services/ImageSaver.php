<?php

namespace AppBundle\Services;

use AppBundle\Entity\ProductImage;
use AppBundle\Entity\ProductImageSize;
use AppBundle\Enums\ImageSizeEnum;
use AppBundle\Repository\ImageSizeRepository;
use Symfony\Component\Filesystem\Filesystem;
use \Eventviva\ImageResize;

// using https://github.com/eventviva/php-image-resize
class ImageSaver
{
    const MAIN_DIR = 'products';

    private $imageSizeRepository;
    private $savePath;
    private $originalPath;
    private $originalName;
    private $filesystem;

    public function __construct(
        string $rootDir,
        ImageSizeRepository $imageSizeRepository,
        Filesystem $filesystem,
        $router
    ) {
        $this->imageSizeRepository = $imageSizeRepository;
        $this->filesystem = $filesystem;
        $this->router = $router;
        $this->savePath = realpath($rootDir . '/../web/' . self::MAIN_DIR);
    }

    public function setProductImage(ProductImage $productImage)
    {
        $this->productImage = $productImage;

        $this->saveOriginal();
        $this->saveThumb();
        //$this->uploadedFile();
    }

    private function saveOriginal()
    {
        $this->originalPath = $this->generateDirName();
        $file = $this->productImage->getFile();
        $this->originalName = $this->newFilename($file->guessExtension());
        $file->move($this->savePath . '/' . $this->originalPath, $this->originalName);

        $this->newImageSize(ImageSizeEnum::ORIGINAL, $this->originalPath);
    }

    private function newImageSize($size, $path)
    {
        $address = $this->router->getContext()->getBaseUrl();
        $address = str_replace('/app_dev.php', '', $address);

        $sizeEntity = new ProductImageSize();
        $sizeEntity->setPath($this->savePath . '/' . $path . '/' . $this->originalName);
        $sizeEntity->setWebAddress($address . '/' . self::MAIN_DIR . '/' . $path . '/' . $this->originalName);
        $sizeEntity->setSize($size);
        $sizeEntity->setName($this->originalName);

        $this->productImage->addSize($sizeEntity);
    }

    private function saveThumb()
    {
        $sizes = $this->imageSizeRepository->findAll();
        foreach ($sizes as $size) {
            $sizePath = $this->originalPath . '/' . $size->getSize();
            if (!$this->filesystem->exists($this->savePath . '/' . $sizePath)) {
                $this->filesystem->mkdir($this->savePath . '/' . $sizePath, 0777);
            }

            $image = new ImageResize($this->savePath . '/' . $this->originalPath . '/' . $this->originalName);

            if ($size->getMethod() === 'crop') {
                $image->crop($size->getWidth(), $size->getHeight());
            } else {
                $image->resizeToBestFit($size->getWidth(), $size->getHeight());
            }
            $image->save($this->savePath . '/' . $sizePath . '/' . $this->originalName);

            $this->newImageSize($size->getSize(), $sizePath);
        }
    }

    private function generateDirName(): string
    {
        $t = md5(time());
        $t = substr($t, 0, 4);

        if (!$this->filesystem->exists($this->savePath . '/' . $t)) {
            $this->filesystem->mkdir($this->savePath . '/' . $t, 0777);
        }

        return $t;
    }

    private function newFilename($ext)
    {
        return crc32(microtime(true)) . '.' . $ext;
    }
}
