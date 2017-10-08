<?php

namespace AppBundle\Manager;

use AppBundle\Repository\CategoryRepository;
use AppBundle\Entity\Category;
use AppBundle\Entity\CategoryLanguage;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryManager extends BaseManager
{
    public function __construct(CategoryRepository $categoryRepository, ValidatorInterface $validator)
    {
        $this->repository = $categoryRepository;
        $this->validator = $validator;
    }

    public function getNewCategory():Category
    {
        $category = new Category();
        //$this->setDefaultValues();

        
        $this->currentEntity = $category;
        return $category;
    }

    public function findOneBy(array $conditions):Category
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
