<?php 

namespace Tests\AppBundle\Manager;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Entity\ProductLanguage;

class ProductManagerTest extends KernelTestCase
{
    private $container;
    private $productManager;

    public function setUp()
    {
        self::bootKernel();

        $this->container = self::$kernel->getContainer();
        $this->productManager = $this->container->get('manager.product');
    }
    
    public function testGetNewProduct()
    {
        $product = $this->productManager->getNewProduct();

        $this->assertInstanceOf('AppBundle\Entity\Product', $product);
    }

    public function _testSave()
    {
        $product = $this->productManager->getNewProduct();
        $product->setSku('12');
        $this->productManager->save();
        $this->assertTrue(true);
    }

    public function testFindOneBy()
    {
        $product = $this->productManager->findOneBy(['id' => 1]);

        $this->assertTrue($product->getId() === 1);
    }

    public function _testAddLanguage()
    {
        $product = $this->productManager->findOneBy(['id' => 1]);
        
        $pl = new ProductLanguage();
        $pl->setLangCode('en')
            ->setName('ironman')
            ->setDescription('expensice ironman');

        $this->productManager->addLanguage($pl);
        $this->productManager->save();
        $this->assertTrue(true);
    }


    public function _testGetProductByLanguageCode()
    {
        $product = $this->productManager->findOneBy(['id' => 1]);
        $lang = $product->getLanguage('en');
        $this->assertTrue($lang->getLangCode() === 'en');
    }

    public function _testRemoveLanguage()
    {
        $product = $this->productManager->findOneBy(['id' => 1]);
        $lang = $product->getLanguage('en');

        $this->productManager->removeLanguage($lang);
        $this->productManager->save();
    }

    public function _testAddCategory()
    {
        $product = $this->productManager->findOneBy(['id' => 1]);
        
        $categoryManager = $this->container->get('manager.category');
        $category = $categoryManager->findOneBy(['id' => 4]);
        //addCategory

        $this->productManager->addCategory($category);
        $this->productManager->save();
        $this->assertTrue(true);
    }

    public function _testRemoveCategory()
    {
        $product = $this->productManager->findOneBy(['id' => 1]);
        
        $categoryManager = $this->container->get('manager.category');
        $category = $categoryManager->findOneBy(['id' => 4]);

        $this->productManager->removeCategory($category);
        $this->productManager->save();
        $this->assertTrue(true);
    }
}
