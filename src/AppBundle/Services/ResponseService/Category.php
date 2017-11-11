<?php

namespace AppBundle\Services\ResponseService;

use AppBundle\Repository\CategoryRepository;

class Category
{
    private $perPage = 20;
    private $page = 0;
    private $categoryRepository;
    private $prodcutImageRepository;

    public function __construct(
        CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    public function byCountry(string $countryCode, int $parentId): array
    {
        $categories = $this->categoryRepository->findBy(['parent' => $parentId === 0 ? null : $parentId], [], $this->perPage, $this->page);

        foreach ($categories as $category) {
            $category->getLanguage($countryCode);
            $this->childrenLanguage($category, $countryCode);
        }

        return $categories;
    }

    private function childrenLanguage($category, $langCode)
    {
        $children = $category->getChildren();
        $count = count($children);
        $category->setChildrenCount($count);
        if ($count <= 0) {
            return;
        }

        foreach ($children as $child) {
            $child->getLanguage($langCode);
            $this->childrenLanguage($child, $langCode);
        }
        return;
    }
}
