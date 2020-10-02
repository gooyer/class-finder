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
        $interfaces = array_map(function(\ReflectionClass $reflectionClass) {
            return $reflectionClass->getName();
        }, $reflectionClass->getInterfaces());

        foreach ($this->interfaces as $requiredInterface) {
            if (!in_array($requiredInterface, $interfaces)) {
                return false;
            }
        }

        return true;
    }
}