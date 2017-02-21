<?php

namespace Cineca\TranslationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\Definition\Processor;

/**
 * This is the class that loads and manages your bundle configuration.
 * 1) To prepend configuration of any bundle before method load is call
 * -- implement ExtensionInterface or extend Extension and implement PrependExtensionInterface
 *    to get the method prepend
 * 2) To execute code during Compilation by writing your own compilePass that implement CompilePassInterface
 *     where you can ovveride the method process. Method process is call after all extensions are loaded
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class CinecaTranslationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        //$processor = new Processor();

        $config = $this->processConfiguration($configuration, $configs);

        //Check tranlation classes
        if(empty($config['translation_classes']))
        {
            throw new \RuntimeException('Cineca Translation bundle need classes to map translation.');
        }

        /*
        if(isset($config['translation_classes']) && empty($config['translation_classes']['token']))
        {
            echo "This bundle need token class to map translation token";
            die;
        }
        */

        if(isset($config['translation_classes']) && empty($config['translation_classes']['translation']))
        {
            throw new \RuntimeException('Cineca Translation bundle need translation class to map translation messages.');
        }

        /*
        if(isset($config['translation_classes']) && empty($config['translation_classes']['language']))
        {
            echo "This bundle need language class to map translation languages";
            die;
        }
        */

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        //Set configuration classes of application to the bundle
        $container->setParameter('cineca_translation.token', $config['translation_classes']['token']);
        $container->setParameter('cineca_translation.translation', $config['translation_classes']['translation']);
        $container->setParameter('cineca_translation.language', $config['translation_classes']['language']);

    }

}
