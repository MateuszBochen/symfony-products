<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Storage;
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
}
