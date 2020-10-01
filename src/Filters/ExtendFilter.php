<?php

declare(strict_types=1);

namespace Gooyer\ClassFinder\Filters;

use Gooyer\ClassFinder\Filter;

class ExtendFilter implements Filter
{
    /**
     * @var string
     */
    private $extendClass;

    public function __construct(string $extendClass)
    {
        $this->extendClass = $extendClass;
    }

    public function __invoke(\ReflectionClass $reflectionClass)
    {

    }
}