<?php

namespace AppBundle\Services\ResponseService;

use AppBundle\Repository\StorageRepository;

class Storage
{
    private $storageRepository;

    public function __construct(StorageRepository $storageRepository)
    {
        $this->storageRepository = $storageRepository;
    }

    public function getList()
    {
        return $this->storageRepository->findAll();
    }
    public function getFullStorage($id)
    {
        return $this->storageRepository->findOneById(['id' => $id]);
    }
}
