<?php

namespace Cineca\TranslationBundle\Services;

use Symfony\Component\Translation\Dumper\DumperInterface;

class DBDumperInterface implements DumperInterface
{
    //private $translationRepository;
    //private $languageRepository;
   //private $container;
    /**
     * Make file backup before the dump.
     *
     * @var bool
     */
   // private $backup = true;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        //EntityManager $entityManager,
        //ContainerInterface $container
        )
    {
        /*
        $this->translationRepository = $entityManager->getRepository("CinecaFareDataBundle:LanguageTranslation");
        $this->languageRepository    = $entityManager->getRepository("CinecaFareDataBundle:Language");
        $this->container             = $container;
        */
    }

    /**
     * {@inheritdoc}
     * Dump inside DB of all messages catalogue
     */
    /*
    public function dump(MessageCatalogue $messages, $options = array())
    {
        if (!array_key_exists('path', $options)) {
            throw new \InvalidArgumentException('The file dumper needs a path option.');
        }

        // save a file for each domain
        foreach ($messages->getDomains() as $domain) {
            // backup
            $fullpath = $options['path'].'/'.$this->getRelativePath($domain, $messages->getLocale());
            if (file_exists($fullpath)) {
                if ($this->backup) {
                    copy($fullpath, $fullpath.'~');
                }
            } else {
                $directory = dirname($fullpath);
                if (!file_exists($directory) && !@mkdir($directory, 0777, true)) {
                    throw new \RuntimeException(sprintf('Unable to create directory "%s".', $directory));
                }
            }
            // save file
            file_put_contents($fullpath, $this->formatCatalogue($messages, $domain, $options));
        }

        //save inside db for each domain
    }
    */
}