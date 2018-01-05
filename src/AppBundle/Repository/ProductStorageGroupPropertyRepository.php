<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ProductStorageGroupProperty;
use Doctrine\ORM\EntityRepository;

class ProductStorageGroupPropertyRepository extends EntityRepository
{
    public function save(ProductStorageGroupProperty $productStorageGroupProperty)
    {
        $this->_em->persist($productStorageGroupProperty);
        $this->_em->flush();
    }

    public function update(ProductStorageGroupProperty $productStorageGroupProperty)
    {
        $this->_em->merge($productStorageGroupProperty);
        $this->_em->flush();
    }

    /**
     *  Delete form all grups using in storage groups
     */
    public function deleteAllByPropertyId(int $propertyId)
    {
        $qb = $this->createQueryBuilder('psgp');
        $qb->delete();
        $qb->where('psgp.productProperty = :propertyId');
        $qb->setParameter('propertyId', $propertyId);

        $qb->getQuery()
            ->execute();
    }
}
