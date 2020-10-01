<?php

declare(strict_types=1);

namespace Gooyer\ClassFinder\Filters;

use Gooyer\ClassFinder\Filter;

class TraitsFilter implements Filter
{
    /**
     * @var array<string>
     */
    private $traits;

    public function __construct(array $traits)
    {
        $this->traits = $traits;
    }

    public function __invoke(\ReflectionClass $reflectionClass)
    {

    }
}