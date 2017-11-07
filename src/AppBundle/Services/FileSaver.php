<?php

namespace AppBundle\Services;

use AppBundle\Entity\ProductFile;
use AppBundle\Helpers\FilesHelper;
use Symfony\Component\Filesystem\Filesystem;

// using https://github.com/eventviva/php-image-resize
class FileSaver extends FilesHelper
{
    const MAIN_DIR = 'files';

    private $originalPath;
    private $originalName;
    private $productFile;

    public function __construct(
        string $rootDir,
        Filesystem $filesystem,
        $router
    ) {

        $this->filesystem = $filesystem;
        $this->router = $router;
        $this->savePath = realpath($rootDir . '/../web/' . self::MAIN_DIR);
    }

    public function removeFileFile(ProductFile $productFile)
    {
        $filePath = $productFile->getFilePath();
        $this->filesystem->remove($filePath);
    }

    public function setProductFile(ProductFile $productFile)
    {
        $this->productFile = $productFile;

        $this->saveOriginal();

    }

    private function saveOriginal()
    {
        $this->originalPath = $this->generateDirName();
        $file = $this->productFile->getFile();
        $this->originalName = $this->newFilename($file->guessExtension());
        $file->move($this->savePath . '/' . $this->originalPath, $this->originalName);

        $this->newFile($this->originalPath, $file->getClientOriginalName());
    }

    private function newFile($path, $trueFilename)
    {
        $address = $this->router->getContext()->getBaseUrl();
        $address = str_replace('/app_dev.php', '', $address);
        $this->productFile->setFilePath($this->savePath . '/' . $path . '/' . $this->originalName);
        $this->productFile->setUrl($address . '/' . self::MAIN_DIR . '/' . $path . '/' . $this->originalName);
        $this->productFile->setFileName($this->originalName);
        $this->productFile->setOriginalName($trueFilename);
    }
}
