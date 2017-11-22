<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ProductProperty;

class ProductPropertyRepository extends \Doctrine\ORM\EntityRepository
{
    public function save(ProductProperty $productProperty)
    {
        $this->_em->persist($productProperty);
        $this->_em->flush();
    }

    public function update(ProductProperty $productProperty)
    {
        $this->_em->merge($productProperty);
        $this->_em->flush();
    }

    public function remove(ProductProperty $productProperty)
    {
        $this->_em->remove($productProperty);
        $this->_em->flush();
    }

    public function deleteById(int $id)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->delete();
        $qb->where('p.id = :id');
        $qb->setParameter('id', $id);
        $qb->getQuery()
            ->execute();
    }
}
