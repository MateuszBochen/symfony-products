<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Storage;

/**
 * StorageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StorageRepository extends \Doctrine\ORM\EntityRepository
{
    public function save(Storage $storage)
    {
        $this->_em->persist($storage);
        $this->_em->flush();
    }

    public function update(Storage $storage)
    {
        $this->_em->merge($storage);
        $this->_em->flush();
    }

    public function remove(Storage $storage)
    {
        $this->_em->remove($storage);
        $this->_em->flush();
    }
}
