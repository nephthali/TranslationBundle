<?php

namespace Cineca\TranslationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use \ReflectionClass;

class DynamicServiceCompilerPass implements CompilePassInterface
{
    public function process(ContainerBuilder $container)
    {
        if($container->hasParameter('cineca_translation.translation_classes.translation'))
        {
            $translation_table = $container->getParameter('cineca_translation.translation_classes.translation');
            $reflector = new ReflectionClass($translation_table);
            $definition = new Definition($reflector::getName());

            $container->setDefinition('cineca_translation.table', $definition);
        }
    }
}