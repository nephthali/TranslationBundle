<?php

namespace Cineca\TranslationBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\ResourceInterface;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\TranslatorInterface;
use Cineca\TranslationBundle\Services\DBConnection;
use Doctrine\DBAL\Connection;

class DBLoader implements LoaderInterface, ResourceInterface
{
    //private $translationRepository;
    //private $languageRepository;
    private $translationClass;
    private $languageClass;
    private $container;
    private $connection;
    protected $options = array(
        'table' => 'translations',
        'columns' => array(
            //key and its translations
            'key' => 'key',
            'translation' => 'translation',

            //For a given locale
            'locale' => 'locale',

            //The domain
            'domain' => 'domain',

            //DateTime of the last_update
            'update_at' => 'updated_at',
        )
    );

    protected $freshnessStatement;
    protected $resourcesStatement;
    protected $translationsStatement;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager, ContainerInterface $container, Connection $dbalConnection, $options)
    {
        //$this->translationRepository = $entityManager->getRepository("CinecaFareDataBundle:LanguageTranslation");
        //$this->languageRepository    = $entityManager->getRepository("CinecaFareDataBundle:Language");

        $this->container             = $container;
        $this->translationClass = $this->container->getParameter("cineca_translation.translation_classes.translation");
        //$this->languageClass    = $this->container->get("cineca_translation.language");
        $this->connection = $dbalConnection;
        $this->options = array_replace_recursive($this->options, $options);
    }

    public function load($resource, $locale, $domain = 'messages')
    {
        //The loader only accepts itself as resource
        if($resource !== $this){
            return new MessageCatalogue($locale);
        }

        /*
        $this->clearLanguageCache();

        //Load on the db for the specified local
        $language = $this->languageRepository->getLanguage($locale);

        //var_dump($language);

        //$translations = $this->translationRepository->getTranslations($language, $domain);
        $translations = $this->translationClass->getTranslations($language, $domain);

        //var_dump($translations);

        $catalogue = new MessageCatalogue($locale);*/

        /**@var $translation Cineca\Fare\PublicBundle\Entity\LanguageTranslation */
        /*
        foreach ($translations as $translation) {
            $catalogue->set($translation->getLanguageToken()->getToken(), $translation->getTranslation(), $domain);
        }
        */

        //var_dump($catalogue->all());die;

        $stmt = $this->getTranslationsStatement();
        $stmt->bindValue(':locale', $locale, \PDO::PARAM_STR);
        $stmt->bindValue(':domain', $domain, \PDO::PARAM_STR);

        if (false === $stmt->execute()) {
            throw new \RuntimeException('Could not fetch translation data from database.');
        }

        //$stmt->bindColumn('key', $key);
        //$stmt->bindColumn('translation', $trans);

        $catalogue = new MessageCatalogue($locale);

        while ($row = $stmt->fetch()) {
            $key = $row['KEY'];
            $trans = $row['TRANSLATION'];
            $catalogue->set($key, $trans, $domain);
        }


        return $catalogue;
    }

    protected function getTranslationsStatement()
    {
        if ($this->translationsStatement instanceof \PDOStatement) {
            return $this->translationsStatement;
        }
        $sql = vsprintf("SELECT %s AS key, %s AS translation FROM %s WHERE %s = :locale AND %s = :domain", array(
            // SELECT ..
            $this->getColumnname('key'),
            $this->getColumnname('translation'),
            // FROM ..
            $this->getTablename(),
            // WHERE ..
            $this->getColumnname('locale'),
            $this->getColumnname('domain'),
        ));
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->setFetchMode(\PDO::FETCH_BOTH);
        $this->translationsStatement = $stmt;
        return $stmt;
    }

    /**
     * Retrieve all locale-domain combinations and add them as a resource to the translator.
     *
     * @param Translator $translator
     *
     * @throws \RuntimeException
     */
    public function registerResources(TranslatorInterface $translator)
    {
        $stmt = $this->getResourcesStatement();
        if (false === $stmt->execute()) {
            throw new \RuntimeException('Could not fetch translation data from database.');
        }

        /*
        bindColumn method doesn't exist for OCI8STATEMENT
        $stmt->bindColumn('locale', $locale);
        $stmt->bindColumn('domain', $domain);
        */

        //$stmt->bindParam('locale', $locale, \PDO::PARAM_STR);
        //$stmt->bindParam('domain', $domain, \PDO::PARAM_STR);

        while ($row = $stmt->fetch()) {
            $locale = $row['LOCALE'];
            $domain = $row['DOMAIN'] != null ? $row['DOMAIN'] : 'messages';
            $translator->addResource('db', $this, $locale, $domain);
        }
    }

    protected function getResourcesStatement()
    {
        if ($this->resourcesStatement instanceof \PDOStatement) {
            return $this->resourcesStatement;
        }
        $sql = vsprintf("SELECT DISTINCT %s AS locale, %s AS domain FROM %s", array(
            // SELECT ..
            $this->getColumnname('locale'),
            $this->getColumnname('domain'),
            // FROM ..
            $this->getTablename(),
        ));
        $stmt = $this->getConnection()->prepare($sql);

        /*
        Available fetch Modes for OCI8STATEMENT are
            PDO::FETCH_BOTH => OCI_BOTH,
            PDO::FETCH_ASSOC => OCI_ASSOC,
            PDO::FETCH_NUM => OCI_NUM,
            PDO::FETCH_COLUMN => OCI_NUM,
        with default PDO::FETCH_BOTH
         */
        $stmt->setFetchMode(\PDO::FETCH_BOTH);
        $this->resourcesStatement = $stmt;
        return $stmt;
    }

    public function isFresh($timestamp)
    {
        $stmt = $this->getFreshnessStatement();
        $stmt->bindParam(':timestamp', $timestamp, \PDO::PARAM_INT);
        // If we cannot fetch from database, keep the cache, even if it's not fresh.
        if (false === $stmt->execute()) {
            return true;
        }
        $stmt->bindColumn(1, $count);
        $stmt->fetch();
        return (bool) $count;
    }

    protected function getFreshnessStatement()
    {
        if ($this->freshnessStatement instanceof \PDOStatement) {
            return $this->freshnessStatement;
        }
        $sql = vsprintf("SELECT COUNT(*) FROM %s WHERE %s > :timestamp", array(
            $this->getTablename(),
            $this->getColumnname('updated_at'),
        ));
        $stmt = $this->con->prepare($sql);
        $stmt->setFetchMode(\PDO::FETCH_COLUMN, 0);
        $this->freshnessStatement = $stmt;
        return $stmt;
    }

    public function __toString()
    {
        return 'PDOLoader::'.base64_encode($this->options);
    }

    public function getResource()
    {
        return $this;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function getTablename()
    {
        return $this->options['table'];
    }

    public function getColumnname($column)
    {
        return $this->options['columns'][$column];
    }

    /**
     * Remove language in every cache directories
     */
    private function clearLanguageCache()
    {
        $cacheDir = $this->container->get('kernel')->getCacheDir();

        $finder = new Finder();
        $finder->in(array($cacheDir . "/translations"))->files();

        foreach ($finder as $file) {
            echo $file->getRealpath() . PHP_EOL;
            unlink($file->getRealpath());
        }
    }

}
