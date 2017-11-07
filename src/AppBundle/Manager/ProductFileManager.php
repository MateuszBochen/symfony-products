<?php

namespace AppBundle\Manager;

use AppBundle\Entity\ProductFile;
use AppBundle\Repository\ProductFileRepository;
use AppBundle\Services\FileSaver;

class ProductFileManager extends BaseManager
{
    private $fileSaver;

    public function __construct(
        FileSaver $fileSaver,
        ProductFileRepository $productFileRepository
    ) {
        $this->fileSaver = $fileSaver;
        $this->repository = $productFileRepository;
    }

    public function findOneBy(array $conditions): ProductFile
    {
        $this->currentEntity = $this->repository->findOneBy($conditions);

        return $this->currentEntity;
    }

    public function addNewFile(ProductFile $file)
    {
        $this->fileSaver->setProductFile($file);
    }

    public function updateFile(ProductFile $file)
    {
        if ($file->getfile()) {
            $this->fileSaver->removeFileFile($file);
            $this->fileSaver->setProductFile($file);
        }
    }

    public function deleteFile(ProductFile $file)
    {
        $this->fileSaver->removeFileFile($file);
    }
}
