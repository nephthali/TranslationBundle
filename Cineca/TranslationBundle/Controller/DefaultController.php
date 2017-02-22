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

        // This Action will be need to return all translation
        return $this->render('CinecaTranslationBundle:Default:index.html.twig');
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
}
