<?php

declare(strict_types=1);

namespace Gooyer\ClassFinder\Filters;

use Gooyer\ClassFinder\Filter;

class InterfacesFilter implements Filter
{
    /**
     * @var array<string>
     */
    private $interfaces;

    public function __construct(array $interfaces)
    {
        $this->interfaces = $interfaces;
    }

    public function __invoke(\ReflectionClass $reflectionClass)
    {
        // TODO: Implement __invoke() method.
    }
}