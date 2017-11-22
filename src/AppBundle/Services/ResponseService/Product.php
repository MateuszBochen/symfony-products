<?php

namespace AppBundle\Services\ResponseService;

use AppBundle\Entity\Product as EntityProduct;
use AppBundle\Repository\ProductImageRepository;
use AppBundle\Repository\ProductRepository;

class Product
{
    const ORDER_COLUMNS = [
        'sku' => 'p.sku',
        'id' => 'p.id',
        'name' => 'pl.name',
    ];

    private $perPage = 10;
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

    public function byCountry(string $countryCode, $word, $page, $orderBy, $orderDir): array
    {
        if (!isset(self::ORDER_COLUMNS[$orderBy])) {
            return [];
        }

        $orderDir = strtoupper($orderDir);
        if (!($orderDir === 'DESC' || $orderDir === 'ASC')) {
            return [];
        }

        $orderColumn = self::ORDER_COLUMNS[$orderBy];

        $products = $this->productRepository->search($countryCode, $word, $this->perPage, $this->perPage * $page, $orderColumn, $orderDir);

        foreach ($products as $product) {
            $product->getLanguage($countryCode);
            $product->getMainImage();
            $product->setCategoriesNames($this->prepareCategories($product, $countryCode));
        }

        $count = $this->productRepository->countAllProducts();

        return [
            'products' => $products,
            'pages' => ceil($count / $this->perPage),
        ];
    }

    /*public function search(string $countryCode, $word, $page, $orderBy, $orderDir)
    {
    $products = $this->productRepository->search($countryCode, $word, $this->perPage, $this->perPage * $page);

    foreach ($products as $product) {
    $product->getLanguage($countryCode);
    $product->getMainImage();
    $product->setCategoriesNames($this->prepareCategories($product, $countryCode));
    }

    $count = $this->productRepository->countAllProducts();

    return [
    'products' => $products,
    'pages' => ceil($count / $this->perPage),
    ];
    }*/

    private function prepareCategories($product, $countryCode)
    {
        $returnArray = [];
        $categories = $product->getCategories();

        foreach ($categories as $category) {
            $lang = $category->getLanguage($countryCode);
            $a = [
                'id' => $category->getId(),
                'language' => $lang,
            ];
            $returnArray[] = $a;
        }

        return $returnArray;
    }

    public function allImages($productId)
    {
        $productImages = $this->productImageRepository->findBy(['product' => $productId], ['position' => 'ASC'], $this->perPage, $this->page);

        foreach ($productImages as $productImage) {
            $productImage->getAlllanguages();
        }

        return $productImages;
    }

    public function allFilesByLanguage($productId, $langCode)
    {
        $product = $this->productRepository->findOneBy(['id' => $productId]);
        return $product->getFilesByLnagCode($langCode);
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

    private function prepareFullProduct(EntityProduct $product)
    {
        $properties = $product->getProperties();

        /*$languagesProps = [];

        foreach ($properties as $property) {
        $property->getLanguages();
        $languagesProps[] = $property; // $property->getLanguages();
        }*/

        $product->getAlllanguages();
        $product->setAllProperties($properties);
        $product->getMainImage();

        return $product;
    }
}
