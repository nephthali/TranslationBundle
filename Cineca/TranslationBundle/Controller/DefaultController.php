<?php

namespace Cineca\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        //get doctrine
        $dm = $this->get('doctrine')->getManager();

        //get the page
        $page = $this->getRequest()->get('page');

        //varible use to paginate
        $paginator = null;

        //translation class name
        $entityClassName = $this->getParameter('cineca_translation.translation_classes.translation');
        if(empty($entityClassName))
        {
            throw new \RuntimeException("This bundle need an entity class name defined under configuration file ");
        }

        $classMetadata = $dm->getClassMetadata($entityClassName);
        if(class_exists($classMetadata))
        {
            throw new \RuntimeException("This bundle need an entity class name defined under configuration file ");
        }
        else
            $repositoryClass = $dm->getRepository($classMetadata->getReflectionClass()->getName());

        $translationsDefined = $repositoryClass->findAll();

        $paginatorCheck = $this->hasPaginatorBundle();

        if($paginatorCheck)
        {
            $paginator = $this->get('knp_paginator');
        }

        if(is_null($paginator))
        {
            throw new \RuntimeException("You need KnpPaginatorBundle to process your request");
        }

        if(!is_null($paginator))
        {
            $pagination = $paginator->paginate(
                $translationsDefined,
                $page,
                10 /*Limit per page*/

            );
        }

        // This Action will be need to return all translation
        return $this->render('CinecaTranslationBundle:Default:index.html.twig',array(
            'pagination' => isset($pagination) ? $pagination : null
        ));
    }

    /**/
    public function newAction()
    {}

    /**/
    public function updateAction($translation)
    {}

    /**/
    public function deleteAction($translation)
    {}

    /*
    * Check Paginator Bundle exist
    */
    private function hasPaginatorBundle()
    {
       if (array_key_exists('KnpPaginatorBundle', $this->get('kernel')->getBundles())) {
           return true;
       }
       return false;
    }
}
