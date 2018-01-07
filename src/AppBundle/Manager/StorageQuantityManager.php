<?php

namespace AppBundle\Manager;

use AppBundle\Entity\StorageQuantity;
use AppBundle\Repository\StorageQuantityRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StorageQuantityManager extends BaseManager
{
    public function __construct(StorageQuantityRepository $storageQuantityRepository, ValidatorInterface $validator)
    {
        $this->repository = $storageQuantityRepository;
        $this->validator = $validator;
    }

    public function getNewStorageQuantity(): StorageQuantity
    {
        $this->currentEntity = new StorageQuantity();

        return $this->currentEntity;
    }

    public function getStorageQuantityBy(array $conditions): StorageQuantity
    {
        $this->currentEntity = $this->repository->findOneBy($conditions);

        return $this->currentEntity;
    }

    public function removeProductFromStroage(int $storageId, int $productId)
    {
        $this->repository->removeProductFromStorage($storageId, $productId);
    }
}
