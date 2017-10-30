<?php

namespace AppBundle\Services\ResponseService;

use AppBundle\Repository\ProductRepository;
use AppBundle\Entity\Product as EntityProduct;

class Product
{
    private $perPage = 20;
    private $page = 0;
    private $productRepository;


    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function byCountry(string $countryCode):array
    {
        $products = $this->productRepository->findBy([], [], $this->perPage, $this->page);
        
        foreach($products as $product) {
            $product->getLanguage($countryCode);
        }

        return $products;
    }

    public function fullProductByProduct(EntityProduct $product):EntityProduct
    {
        return $this->prepareFullProduct($product);
    }

    public function fullProduct(int $productId)
    {
        $product = $this->productRepository->findOneBy(['id' => $productId]);
        return $this->prepareFullProduct($product);
    }

    private function prepareFullProduct($product)
    {
        $product->getAlllanguages();
        $product->getAllProperties();

        return $product;
    }
}
