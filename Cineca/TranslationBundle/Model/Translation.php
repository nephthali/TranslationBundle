<?php

namespace Cineca\TranslationBundle\Model;

/**
 * @author Nephthali Djabon Tchounda <neph@cineca.it>
**/

use Doctrine\ORM\Mapping as ORM;


/**
 * Translation
 *
 * @ORM\MappedSuperclass
 */
class Translation
{
    /**
     * @var string
     *
     * @ORM\Column(name="key", type="string", length=200)
     */
    private $key;

    /**
     * @var string
     *
     * @ORM\Column(name="translation", type="text", nullable=true)
     */
    private $translation;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=5, nullable=true)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=255, nullable=true)
     */
    private $domain;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_at", type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * Set key
     *
     * @param string $key
     * @return Translations
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set translation
     *
     * @param string $translation
     * @return Translations
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;

        return $this;
    }

    /**
     * Get translation
     *
     * @return string
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return Translations
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set domain
     *
     * @param string $domain
     * @return Translations
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set updateAt
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    /*
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = new \DateTime();
    }
    */

    /**
     * Get updateAt
     *
     * @return \DateTime
     */
    /*
    public function getUpdateAt()
    {
        return $this->updateAt;
    }
    */


}


/*
abstract class Translation implements TranslationInterface
{
    protected $id;
    protected $key;
    protected $translation;
    protected $domain;

    public function __construct(
        //$id,
        //$key
    )
    {
        //$this->id = $id;
        //$this->key = $key;
    }


}
*/