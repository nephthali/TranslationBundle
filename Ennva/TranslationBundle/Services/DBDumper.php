<?php

namespace Cineca\TranslationBundle\Services;

use Symfony\Component\Translation\MessageCatalogue;
use Cineca\Translation\Services\DBDumperInterface;
use Cineca\Translation\Services\DBLoader;
use Cineca\TranslationBundle\Services\DBConnection;

class DBDumper extends DBLoader implements DBDumperInterface
{
    //private $translationRepository;
    //private $languageRepository;
    //private $translationClass;
   //private $languageClass;
    //private $container;
    //protected $connection;
    protected $insertStatement;
    protected $updateStatement;
    protected $selectStatement;

    /**
     * @param EntityManager $entityManager
     */
    /*
    public function __construct(EntityManager $entityManager, ContainerInterface $container, DBConnection $dbalConnection)
    {
        //$this->translationRepository = $entityManager->getRepository("CinecaFareDataBundle:LanguageTranslation");
        //$this->languageRepository    = $entityManager->getRepository("CinecaFareDataBundle:Language");
        $this->container             = $container;
        $this->translationClass = $this->container->get("cineca_translation.translation");
        //$this->languageClass    = $this->container->get("cineca_translation.language");
        $this->connection = $dbalConnection;
        $this->options = array_replace_recursive($this->options, $options);
    }
    */

    /**
     * Dumps the message catalogue.
     *
     * @param MessageCatalogue $messages The message catalogue
     * @param array            $options  Options that are used by the dumper
     */
    public function dump(MessageCatalogue $messages, $options = array())
    {
        $this->connection->beginTransaction();

        $insertStmt = $this->getInsertStatement();
        $updateStmt = $this->getUpdateStatement();
        $selectStmt = $this->getSelectStatement();

        $now = strtotime('now');

        $locale = $messages->getLocale();
        foreach ($messages->getDomains() as $eachDomain) {
            foreach ($messages->all($eachDomain) as $eachKey => $eachTranslation) {
                $selectStmt->bindValue(':locale', $locale);
                $selectStmt->bindValue(':domain', $eachDomain);
                $selectStmt->bindValue(':key', $eachKey);
                if (false === $selectStmt->execute()) {
                    throw new \RuntimeException('Could not fetch translation data from database.');
                }
                $currentTranslation = null;
                $selectStmt->bindColumn('translation', $currentTranslation);
                $selectStmt->fetch();
                $dumpStmt = null;
                if (null === $currentTranslation) {
                    $dumpStmt = $insertStmt;
                } else {
                    if ($currentTranslation === (string) $eachTranslation) {
                        continue;
                    }
                    $dumpStmt = $updateStmt;
                }
                $dumpStmt->bindValue(':key', $eachKey);
                $dumpStmt->bindValue(':translation', (string) $eachTranslation);
                $dumpStmt->bindValue(':locale', $locale);
                $dumpStmt->bindValue(':domain', $eachDomain);
                $dumpStmt->bindValue(':updated_at', $now, \PDO::PARAM_INT);
                $dumpStmt->execute();
            }
        }
        if (!$this->connection->commit()) {
            $this->connection->rollBack();
            throw new \RuntimeException(sprintf('An error occurred while committing the transaction. [%s: %s]', $this->connection->errorCode(), $this->connection->errorInfo()));
        }
    }

    protected function getInsertStatement()
    {
        if ($this->insertStatement instanceof \PDOStatement) {
            return $this->insertStatement;
        }
        $sql = vsprintf('INSERT INTO `%s` (`%s`, `%s`, `%s`, `%s`, `%s`) VALUES (:key, :translation, :locale, :domain, :updated_at)', array(
            // INSERT INTO ..
            $this->getTablename(),
            // ( .. )
            $this->getColumnname('key'),
            $this->getColumnname('translation'),
            $this->getColumnname('locale'),
            $this->getColumnname('domain'),
            $this->getColumnname('updated_at'),
        ));
        $stmt = $this->getConnection()->prepare($sql);
        $this->insertStatement = $stmt;
        return $stmt;
    }

    protected function getUpdateStatement()
    {
        if ($this->updateStatement instanceof \PDOStatement) {
            return $this->updateStatement;
        }
        $sql = vsprintf('UPDATE `%s` SET `%s` = :translation, `%s` = :updated_at WHERE `%s` = :key AND `%s` = :locale AND `%s` = :domain', array(
            // UPDATE ..
            $this->getTablename(),
            // SET ( .. )
            $this->getColumnname('translation'),
            $this->getColumnname('updated_at'),
            // WHERE ..
            $this->getColumnname('key'),
            $this->getColumnname('locale'),
            $this->getColumnname('domain'),
        ));
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $this->updateStatement = $stmt;
        return $stmt;
    }

    protected function getSelectStatement()
    {
        if ($this->selectStatement instanceof \PDOStatement) {
            return $this->selectStatement;
        }
        $sql = vsprintf('SELECT `%s` AS `translation` FROM `%s` WHERE `%s` = :locale AND `%s` = :domain AND `%s` = :key', array(
            // SELECT ..
            $this->getColumnname('translation'),
            // FROM ..
            $this->getTablename(),
            // WHERE ..
            $this->getColumnname('locale'),
            $this->getColumnname('domain'),
            $this->getColumnname('key'),
        ));
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->setFetchMode(\PDO::FETCH_COLUMN, 0);
        $this->selectStatement = $stmt;
        return $stmt;
    }
}