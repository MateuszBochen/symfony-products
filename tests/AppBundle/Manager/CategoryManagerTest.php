<?php

namespace Tests\AppBundle\Manager;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Entity\CategoryLanguage;

class CategoryManagerTest extends KernelTestCase
{
    private $container;
    private $categoryManager;

    public function setUp()
    {
        self::bootKernel();

        $this->container = self::$kernel->getContainer();
        $this->categoryManager = $this->container->get('manager.category');
    }

    public function testGetNewProduct()
    {
        $category = $this->categoryManager->getNewCategory();

        $this->assertInstanceOf('AppBundle\Entity\Category', $category);
    }

    public function _testSave()
    {
        $category = $this->categoryManager->getNewCategory();
        $this->categoryManager->save();
        $this->assertTrue(true);
    }

    public function _testSaveChild()
    {
    	$parent = $this->categoryManager->findOneBy(['id' => 1]);

    	$childManager = $this->container->get('manager.category');
        $child = $childManager->getNewCategory();

        $parent->addChild($child);

        $this->categoryManager->save();
        $this->assertTrue(true);
    }

    public function _testAddLanguage()
    {
        $category = $this->categoryManager->findOneBy(['id' => 1]);
        
        $pl = new CategoryLanguage();
        $pl->setLangCode('pl')
            ->setName('zelasko')
            ->setDescription('luksusowe zelasko');

        $this->categoryManager->addLanguage($pl);
        $this->categoryManager->save();
        $this->assertTrue(true);
    }

    public function _testRemoveLanguage()
    {
        $category = $this->categoryManager->findOneBy(['id' => 1]);
        $lang = $category->getLanguage('en');

        $this->categoryManager->removeLanguage($lang);
        $this->categoryManager->save();
        $this->assertTrue(true);
    }
}