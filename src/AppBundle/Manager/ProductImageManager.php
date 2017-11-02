<?php

namespace AppBundle\Manager;

use AppBundle\Entity\ProductImage;
use AppBundle\Repository\ProductImageRepository;
use AppBundle\Services\ImageSaver;

class ProductImageManager extends BaseManager
{
    private $imageSaver;
    private $productImage;

    public function __construct(
        ProductImageRepository $productImageRepository,
        ImageSaver $imageSaver
    ) {
        $this->imageSaver = $imageSaver;
        $this->repository = $productImageRepository;
    }

    public function findOneBy(array $conditions): ProductImage
    {
        $this->currentEntity = $this->repository->findOneBy($conditions);

        return $this->currentEntity;
    }

    public function addNewImage(ProductImage $productImage)
    {
        $this->productImage = $productImage;
        $this->productImage->setPosition($this->getNextPos());
        $this->imageSaver->setProductImage($productImage);
        $this->setAsMain();
    }

    public function updateImage(ProductImage $productImage)
    {
        $this->productImage = $productImage;
        $this->setAsMain();
        if ($this->productImage->getFile()) {
            $this->imageSaver->deleteProductImage($productImage);
            $this->imageSaver->setProductImage($productImage);
        }
    }

    public function deleteImage(productImage $image)
    {
        $this->imageSaver->deleteProductImage($image);
    }

    public function reorderImages($productId, $from, $to)
    {
        $new = $this->repository->getIdsByPos($productId);
        array_splice($new, $to, 0, array_splice($new, $from, 1));
        foreach ($new as $key => $item) {
            $this->repository->updateScalarUpdate('position', $key + 1, $item['id']);
        }
    }

    private function setAsMain()
    {
        if ($this->productImage->getMain()) {
            $product = $this->productImage->getProduct();
            $this->repository->resetMainImagesForProduct($this->productImage->getProduct());
        }
    }

    private function getNextPos()
    {
        return $this->repository->getNextPos($this->productImage->getProduct()) + 1;
    }

}
