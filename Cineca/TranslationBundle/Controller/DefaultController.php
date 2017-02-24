<?php

namespace Cineca\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cineca\TranslationBundle\Form\TranslationsType;
use Symfony\Component\HttpFoundation\Request;
use Cineca\TranslationBundle\Model\Translation;

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
        $translationEntityManager = $this->get('cineca_translation.manager');

        //$entityClassName = $this->getParameter('cineca_translation.translation_classes.translation');
        $entityClassName = $translationEntityManager->getEntityClassName();
        if(empty($entityClassName))
        {
            throw new \RuntimeException("This bundle need an entity class name defined under configuration file ");
        }

        //$classMetadata = $dm->getClassMetadata($entityClassName);
        $classMetadata = $translationEntityManager->getClassMetadata($entityClassName);
        if(class_exists($classMetadata))
        {
            throw new \RuntimeException("This bundle need an entity class name defined under configuration file ");
        }
        else
            $repositoryClass = $translationEntityManager->getRepositoryClass();
            //$repositoryClass = $dm->getRepository($classMetadata->getReflectionClass()->getName());

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

    /**
     * Creates a new translation entity.
     *
     */
    public function newAction(Request $request)
    {
        $translationEntityManager = $this->get('cineca_translation.manager');
        $entityClassName = $translationEntityManager->getEntityClassName();
        $classMetadata = $translationEntityManager->getClassMetadata($entityClassName);

        $translationNewInstance = $classMetadata->newInstance();

        $translation = new Translation();

        //locales defined in config;
        $locales = $this->container->getParameter('locale_array');

        //locales defined in config;
        $locales = $this->container->getParameter('locale_array');

        // For Symfony 2.8 to up Create FormBuilder changed
        // it Need a Fully Qualified name of class like this
        /*
        $form = $this->container->get('form.factory')->create( '\Cineca\TranslationBundle\Form\TranslationsType',
            $translationNewInstance
            //$translation
            ,array('data_class' => get_class($translationNewInstance),
                   'container' => $this->container,
                   'locales' => $locales)
        );
        */

        //$form = $this->createForm('AppBundle\Form\TranslationsType', $translation);
        $form = $this->container->get('form.factory')->create(new TranslationsType($locales),
            $translationNewInstance
            //$translation
            ,array('data_class' => get_class($translationNewInstance))
        );

        //$form = $this->container->get('form.factory')->create(new TranslationsType($locales),$translation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($translation);
            $em->flush($translation);

            return $this->redirectToRoute('cineca_translations_show', array('id' => $translation->getId()));
        }

        return $this->render('CinecaTranslationBundle:Default:new.html.twig', array(
            'translation' => $translation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a translation entity.
     *
     */
    public function showAction(Translations $translation)
    {
        $deleteForm = $this->createDeleteForm($translation);

        return $this->render('CinecaTranslationBundle:Default:show.html.twig', array(
            'translation' => $translation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing translation entity.
     *
     */
    public function editAction(Request $request, Translations $translation)
    {
        $deleteForm = $this->createDeleteForm($translation);

        $form = $this->container->get('form.factory')->create(new LoginType($idp, $data['last_username'], $sitename));


        $editForm = $this->createForm('AppBundle\Form\TranslationsType', $translation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('translations_edit', array('id' => $translation->getId()));
        }

        return $this->render('translations/edit.html.twig', array(
            'translation' => $translation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a translation entity.
     *
     */
    public function deleteAction(Request $request, Translations $translation)
    {
        $form = $this->createDeleteForm($translation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($translation);
            $em->flush($translation);
        }

        return $this->redirectToRoute('translations_index');
    }


    /**
     * Creates a form to delete a translation entity.
     *
     * @param Translations $translation The translation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Translations $translation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('translations_delete', array('id' => $translation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

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
