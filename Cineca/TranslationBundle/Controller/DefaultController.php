<?php

namespace Cineca\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CinecaTranslationBundle:Default:index.html.twig');
    }
}
