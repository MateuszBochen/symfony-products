<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Category;
use AppBundle\Entity\CategoryLanguage;
use AppBundle\Repository\CategoryRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryManager extends BaseManager
{
    public function __construct(CategoryRepository $categoryRepository, ValidatorInterface $validator)
    {
        $this->repository = $categoryRepository;
        $this->validator = $validator;
    }

    public function getNewCategory(): Category
    {
        $category = new Category();
        $this->currentEntity = $category;
        return $category;
    }

    public function createNewCategory(string $name, int $parentId, string $langCode)
    {
        $this->currentEntity = new Category();
        $this->currentEntity->setMainName($name);

        $language = new CategoryLanguage();
        $language->setLangCode($langCode);
        $language->setName($name);
        $language->setDescription('');

        $this->currentEntity->addLanguage($language);

        $this->save();

        if ($parentId === 0) {

            $ip = $this->currentEntity->getId() . '.';
            $this->currentEntity->setIp($ip);
            $this->save();

            return true;
        }

        $parent = $this->repository->getParent($parentId);
        $ip = $parent->getIp() . $this->currentEntity->getId() . '.';
        $this->currentEntity->setIp($ip);
        $parent->addChild($this->currentEntity);

        $this->save();
    }

    public function updateCategory(int $categoryId, string $name, string $langCode)
    {
        $this->currentEntity = $this->repository->findOneBy(['id' => $categoryId]);
        $language = $this->currentEntity->getLanguage($langCode);

        $this->currentEntity->setMainName($name);
        if ($language) {
            $language->setName($name);
        } else {
            $language = new CategoryLanguage();
            $language->setLangCode($langCode);
            $language->setName($name);
            $this->addLanguage($language);
        }

        $this->save();
    }

    public function findOneBy(array $conditions): Category
    {
        $this->currentEntity = $this->repository->findOneBy($conditions);

        return $this->currentEntity;
    }

    public function addChild(Category $category)
    {
        $errors = $this->validator->validate($category);

        if ($errors->count()) {
            throw new ManagerValidationException($errors);
        }

        $this->currentEntity->addChild($category);
    }

    public function removeLanguage(CategoryLanguage $language)
    {
        $this->currentEntity->removeLanguage($language);
    }

    public function addLanguage(CategoryLanguage $language)
    {
        $errors = $this->validator->validate($language);

        if ($errors->count()) {
            throw new ManagerValidationException($errors);
        }

        $this->currentEntity->addLanguage($language);
    }
}
