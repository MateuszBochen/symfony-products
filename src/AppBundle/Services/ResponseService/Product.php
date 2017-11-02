<?php

namespace AppBundle\Services\ResponseService;

use AppBundle\Entity\Product as EntityProduct;
use AppBundle\Repository\ProductImageRepository;
use AppBundle\Repository\ProductRepository;

class Product
{
    private $perPage = 20;
    private $page = 0;
    private $productRepository;
    private $prodcutImageRepository;

    public function __construct(
        ProductRepository $productRepository,
        ProductImageRepository $productImageRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productImageRepository = $productImageRepository;
    }

    public function byCountry(string $countryCode): array
    {
        $products = $this->productRepository->findBy([], [], $this->perPage, $this->page);

        foreach ($products as $product) {
            $product->getLanguage($countryCode);
            $product->getMainImage();
        }

        return $products;
    }

    public function allImages($productId)
    {
        $productImages = $this->productImageRepository->findBy(['product' => $productId], ['position' => 'ASC'], $this->perPage, $this->page);

        foreach ($productImages as $productImage) {
            $productImage->getAlllanguages();
        }

        return $productImages;
    }

    public function fullProductByProduct(EntityProduct $product): EntityProduct
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
        $product->getMainImage();

        return $product;
    }
}
