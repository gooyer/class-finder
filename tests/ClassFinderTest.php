<?php

declare(strict_types=1);

namespace Gooyer\ClassFinder\Tests;

use Composer\Autoload\ClassLoader;

class ClassFinderTest extends \PHPUnit\Framework\TestCase
{
    public function testClassLoader()
    {
        /**
         * @var ClassLoader $classLoader
         */
        $classLoader = require(realpath(__DIR__ . "/../vendor/autoload.php"));
        var_dump($classLoader->getPrefixesPsr4());
    }

}