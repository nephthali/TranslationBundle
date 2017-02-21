<?php

namespace Cineca\TranslationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

class DynamicServiceCompilerPass implements CompilePassInterface
{
    public function process(ContainerBuilder $container)
    {
        if($container->hasParameter('cineca_translation.translation_classes.translation'))
        {
            $definition = new Definition($container->getParameter('cineca_translation.translation_classes.translation')::class);

            $container->setDefinition('cineca_translation.table', $definition);
        }
    }
}