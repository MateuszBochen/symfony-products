<?php

namespace AppBundle\Manager;

class BaseManager
{
    protected $currentEntity;
    protected $validator;
    protected $repository;

    public function save()
    {
        $errors = $this->validator->validate($this->currentEntity);

        if ($errors->count()) {
            throw new ManagerValidationException($errors);
        }

        if ($this->currentEntity->getId()) {
            $this->repository->update($this->currentEntity);
        }
        else {
            $this->repository->save($this->currentEntity);
        }
    }

    public function remove()
    {
        $this->repository->remove($currentEntity);
    }
}