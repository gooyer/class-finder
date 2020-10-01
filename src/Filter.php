<?php

namespace Gooyer\ClassFinder;


interface FilterInterface
{
    public function __invoke(\ReflectionClass $reflectionClass);
}