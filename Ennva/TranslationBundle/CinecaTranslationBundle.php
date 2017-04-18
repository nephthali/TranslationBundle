<?php

namespace Cineca\TranslationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Cineca\TranslationBundle\DependencyInjection\Compiler\DynamicServiceCompilerPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

/*
 * 1) If this Bundle extend another one,ovveride the method Getparent() that return
 *    the Bundle name your need to extend
 * 2) To add your compilePass here ovveride method build that take a given ContainerInterface
 *    as you can manage some services definition in your CompilePass
 */
class CinecaTranslationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        // To set dynamically service class and service id
        $container->addCompilerPass(new DynamicServiceCompilerPass(), PassConfig::TYPE_AFTER_REMOVING);
    }
}
