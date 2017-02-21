<?php

namespace Cineca\TranslationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/*
 * 1) If this Bundle extend another one,ovveride the method Getparent() that return
 *    the Bundle name your need to extend
 * 2) To add your compilePass here ovveride method build that take a given ContainerInterface
 *    as you can manage some services definition in your CompilePass
 */
class CinecaTranslationBundle extends Bundle
{
}
