# CinecaTranslationBundle #

## Introduction ##
Questo bundle affronta i concetti di internalizzazione e localizzazione. Adatti  alle applicazioni Symfony.

Le Features implementate sono:

*    Estrazione delle traduzioni dal database.
*    Interfaccia Web-based per Gestire le traduzioni.

## Documentation ##

### <u>Prerequisiti</u> ###
Questo Bundle Necessita le seguente librerie php per funzinare correttamente

1.    <b>Symfony</b> 2.8.*.
2.    <b>doctrine/orm</b> dalla 2.4.8 in poi.
3.    <b>doctrine/doctrine-bundle</b> dalla 1.4 in poi.

Alternativamente per Gestire le traduzioni

4.    <b>knplabs/knp-paginator-bundle</b> dalla 2.5 in poi.

### <u>Installazione</u> ###

* <b>Aggiungere Nel file composer.json del progetto</b>
  <pre>
    "repositories": [{
        "type": "composer",
        "url": "http://miur-home.dev.cineca.it/satis/html"
    }],
    "require" : {
       // ...
       "cineca/translationbundle": "dev-master"
    }
  </pre>
* <b>Installare o aggiornare le librerie con composer</b>
    <code>composer install</code>
    Oppure
    <code>composer update</code>

* <b>Abilitare il bundle nell'applicazione</b>
    <pre>
    // in AppKernel::registerBundles()
    $bundles = array(
        // ...
        new Cineca\TranslationBundle\CinecaTranslationBundle(),
        // ...
    );
    </pre>

* <b>Alternativamente abitare il bundle KnpPaginatorBundle per gestire le traduzioni</b>
    <pre>
    // in AppKernel::registerBundles()
    $bundles = array(
        // ...
        new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
        // ...
    );
    </pre>

### <u>Configurazione</u> ###

* <b>Importante creare il file col formato <code>domain.locale.extension</code> nella cartella <code>app/Resources/translations</code> dell'applicazione.</b>
* <b>Il bundle usa l'estenzione "db" per caricare le traduzioni definiti nell'applicazione. <code>Esempio di file messages.it.db</code></b>


<b>Il bundle richiede una entity che mappa la tabella delle traduzioni</b>

* <b>Abilitare la componente translator </b>
<pre>
    framework:
        # ...
        translator: { fallbacks: ['%locale%'] }
    #...
    parameters:
        # ...
        locale:     it
</pre>

* <b>Aggiungere nel file di configuration dell'applicazione</b>
<pre>
    # ...config.yml
    cineca_translation:
        translation_classes:
            translation: "path della entity" ##AppBundle\Entity\Translation
</pre>

* <b>Aggiungere la route per accedere all'interfaccia di gestione delle traduzioni. Preferibilmente sotto firewall </b>
<pre>
    # ... routing.yml
    cineca_translation:
        resource: "@CinecaTranslationBundle/Resources/config/routing.xml"
        prefix: /your_prefix
        options:
            i18n: true
</pre>

* <b>Altra documentation </b>


