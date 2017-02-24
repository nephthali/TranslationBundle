<?php

namespace Cineca\TranslationBundle\Services;

use Doctrine\ORM\EntityManager;
//use Cineca\TranslationBundle\Model\TranslationManager as BaseTranslationManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CinecaTranslationManager
{
    private $entityClassName;
    private $entityManager;

    private $container;

    public function __construct(EntityManager $em, $translationClass = null)
    {
        $this->entityManager = $em;
        $this->entityClassName = $translationClass;
        if(is_null($translationClass))
            $this->entityClassName = $this->container->get('cineca_translation.translation_classes.translation');
    }

    public function setContainer(ContainerInterface $container) {
        $this->container = $container;
        return $this;
    }

    public function getEntityClassName()
    {
        return $this->entityClassName;
    }

    public function getClassMetadata()
    {
        if(is_null($this->entityClassName))
            return null;

        $classMetadata = $this->entityManager->getClassMetadata($this->entityClassName);

        return $classMetadata;
    }

    public function getReflectionClass()
    {
        if($this->getClassMetadata() != null)
        {
            return $this->getClassMetadata()->getReflectionClass()->getName();
        }
        else
            return null;
    }

    public function getRepositoryClass()
    {
        if($this->getReflectionClass() != null)
        {
            return $this->entityManager->getRepository($this->getReflectionClass());
        }
        else
            return null;
    }

    public function getEntityFieldNames()
    {
        return $this->getClassMetadata()->getFieldNames();
    }

    public function getEntityTableMapping()
    {
        return $this->getClassMetadata()->getTableName();
    }

}