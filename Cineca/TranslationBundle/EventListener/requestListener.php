<?php

namespace Cineca\Fare\PublicBundle\EventListener;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Kernel;

/**
 *
 */
class requestListener
{
    protected $requestStack;
    private $container;
    private $kernel;
    private $event;

    public function __construct(RequestStack $requestStack, Kernel $kernel)
    {
        $this->requestStack = $requestStack;
        $this->kernel       = $kernel;
    }

    protected function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function onKernelRequest()
    {
        //var_dump($this->getRequest());die;
        //var_dump($this->kernel->getCacheDir());die;
        //var_dump($this->kernel->getEnvironment());die;

        $this->clearLanguageCache();

    }

    /**
     * Remove language in every cache directories
     */
    private function clearLanguageCache()
    {
        $cacheDir              = $this->kernel->getCacheDir();
        $translationsDirectory = $cacheDir . "/translations";

        if (file_exists($translationsDirectory)) {

            $finder = new Finder();

            $finder->in(array($cacheDir . "/translations"))->files();

            foreach ($finder as $file) {
                if ($file) {
                    //echo $file->getRealpath() . PHP_EOL;
                    unlink($file->getRealpath());
                }

            }
        } else {

            return $this->getRequest();

        }

    }
}
