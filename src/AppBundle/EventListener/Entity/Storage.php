<?php

namespace AppBundle\EventListener\Entity;

use AppBundle\Entity\Storage as StorageEntity;
use Doctrine\ORM\Event\LifecycleEventArgs;

class Storage
{
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        /** @var MyEntity $document */
        $storage = $eventArgs->getEntity();
        if (!($storage instanceof StorageEntity)) {
            return;
        }
        $em = $eventArgs->getEntityManager();
        $repo = $em->getRepository('AppBundle:StorageQuantity');
        $quantity = $repo->getProductsQuantity($storage->getId());
        $hasEmpty = $repo->checkIfSomeProductIsEmpty($storage->getId());
        // var_dump($quantity);exit();
        $storage->setProductsQuantity($quantity * 1);
        $storage->setHasEmptyProduct($hasEmpty);
        // $document->setEntityManager($eventArgs->getEntityManager());

    }
}
