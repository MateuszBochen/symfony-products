<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ProductStorageGroup;

class ProductStorageGroupRepository extends \Doctrine\ORM\EntityRepository
{
    public function save(ProductStorageGroup $productStorageGroup)
    {
        $this->_em->persist($productStorageGroup);
        $this->_em->flush();
    }

    public function update(ProductStorageGroup $productStorageGroup)
    {
        $this->_em->merge($productStorageGroup);
        $this->_em->flush();
    }

    public function remove(ProductStorageGroup $productStorageGroup)
    {
        $this->_em->remove($productStorageGroup);
        $this->_em->flush();
    }
}
