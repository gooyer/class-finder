<?php

namespace Gooyer\ClassFinder;


interface Filter
{
    public function __invoke(\ReflectionClass $reflectionClass);
}