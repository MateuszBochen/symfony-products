<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Product;
use AppBundle\Entity\Storage;
use AppBundle\Entity\StorageQuantity;
use AppBundle\Repository\StorageRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StorageManager extends BaseManager
{
    public function __construct(StorageRepository $storageRepository, ValidatorInterface $validator)
    {
        $this->repository = $storageRepository;
        $this->validator = $validator;
    }

    public function getNewStorage(): Storage
    {
        $this->currentEntity = new Storage();

        return $this->currentEntity;
    }

    public function getStorageBy(array $conditions): Storage
    {
        $this->currentEntity = $this->repository->findOneBy($conditions);

        return $this->currentEntity;
    }

    public function addProductToStorage(Storage $storage, Product $product)
    {
        $sq = new StorageQuantity();
        $sq->setProduct($product);
        $sq->setQuantity(0);
        $sq->setPropertyId(0);
        $sq->setPropertyValueId(0);
        $storage->addStorageQuantity($sq);
    }
}
