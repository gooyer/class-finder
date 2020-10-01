<?php

namespace Gooyer\ClassFinder;

use Gooyer\ClassFinder\Exceptions\NamespaceRequiredException;

interface ClassFinderInterface extends \IteratorAggregate
{
    /**
     * @param string $namespace
     * @return ClassFinderInterface
     */
    public function namespace(string $namespace): ClassFinderInterface;


    /**
     * @param string|array<string> $className
     * @return ClassFinderInterface
     */
    public function implements($className): ClassFinderInterface;

    /**
     * @param string $className
     * @return ClassFinderInterface
     */
    public function extends(string $className): ClassFinderInterface;

    /**
     * @param string|array<string> $traits
     * @return ClassFinderInterface
     */
    public function traits($traits): ClassFinderInterface;

    /**
     * @param \Closure|Filter $filter
     * @return ClassFinderInterface
     */
    public function filter($filter): ClassFinderInterface;

    /**
     * @throws NamespaceRequiredException
     * @return array
     */
    public function search(): array;
}