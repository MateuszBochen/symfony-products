<?php

namespace AppBundle\Repository;

use AppBundle\Entity\StorageQuantity;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * StorageQuantityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StorageQuantityRepository extends \Doctrine\ORM\EntityRepository
{
    public function save(StorageQuantity $storage)
    {
        $this->_em->persist($storage);
        $this->_em->flush();
    }

    public function update(StorageQuantity $storage)
    {
        $this->_em->merge($storage);
        $this->_em->flush();
    }

    public function remove(StorageQuantity $storage)
    {
        $this->_em->remove($storage);
        $this->_em->flush();
    }

    public function removeProductFromStorage(int $storageId, int $productId)
    {
        $qb = $this->createQueryBuilder('sq');
        $qb->delete();
        $qb->where('sq.storage = :storageId');
        $qb->andWhere('sq.product = :productId');
        $qb->setParameter('storageId', $storageId);
        $qb->setParameter('productId', $productId);

        $qb->getQuery()
            ->execute();
    }

    public function getProductsQuantity(int $storageId)
    {
        $qb = $this->createQueryBuilder('sq');
        $qb->select('count(DISTINCT sq.product)')
            ->where('sq.storage = :storageId')
            ->setParameter('storageId', $storageId);
        try {
            return $qb->getQuery()
                ->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return 0;
        }
    }

    public function checkIfSomeProductIsEmpty(int $storageId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('min', 'min');
        $query = $this->_em->createNativeQuery('SELECT MIN(s1.quantity) as min
		    FROM storage_quantity s1
		    WHERE s1.id NOT IN (SELECT s2.id FROM storage_quantity s2
		    	WHERE s2.product_id = s1.product_id
		    		AND s2.storage_id = s1.storage_id
		    		AND s2.property_storage_group_id IS NULL
		    		AND (SELECT COUNT(s3.property_storage_group_id)
		    	FROM storage_quantity s3
		    	WHERE s3.product_id = s2.product_id
		    	AND s3.storage_id = s2.storage_id) > 0)
		    AND s1.storage_id = ?
		    GROUP BY storage_id', $rsm);
        $query->setParameter(1, $storageId);

        $results = $query->getResult();

        if (is_array($results) && isset($results[0])) {
            if ($results[0]['min'] <= 0) {
                return true;
            }
        }

        return false;
    }

    public function getStorageQuantity(int $storageId)
    {
        $qb = $this->createQueryBuilder('sq');
        $qb->select('SUM(sq.quantity)')
            ->where('sq.storage = :storageId')
            ->setParameter('storageId', $storageId);
        return $qb->getQuery()
            ->getSingleScalarResult();
    }
}
