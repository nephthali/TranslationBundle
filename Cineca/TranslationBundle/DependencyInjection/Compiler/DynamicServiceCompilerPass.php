<?php

namespace Cineca\TranslationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class DynamicServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        /*
        if($container->hasParameter('cineca_translation.translation_classes.translation'))
        {
             $translation_table = $container->getParameter('cineca_translation.translation_classes.translation');

            $configuration = $container->setDefinition(sprintf('%s', $translation_table), new DefinitionDecorator('cineca_translation.table'));

            if($container->hasDefinition('doctrine'))
            {

            }
            //$reflector = new ReflectionClass($translation_table);
            //$definition = new Definition(get_class($translation_table));

            //$container->setDefinition('cineca_translation.table', $definition);
        }
        */
    }
}