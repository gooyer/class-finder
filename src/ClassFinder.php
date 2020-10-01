<?php

namespace Gooyer\ClassFinder;


use Closure;
use Composer\Autoload\ClassLoader;
use Gooyer\ClassFinder\Exceptions\NamespaceRequiredException;
use Gooyer\ClassFinder\Filters\ExtendFilter;
use Gooyer\ClassFinder\Filters\InterfacesFilter;
use Gooyer\ClassFinder\Filters\TraitsFilter;

class ClassFinder implements ClassFinderInterface
{
    use ApplyFilters {
        filter as applyFilter;
    }

    /**
     * @var ClassLoader
     */
    private $classLoader;

    /**
     * @var array<string>
     */
    private $results;

    /**
     * @var string|null
     */
    private $namespace = null;

    /**
     * @var array<Closure>
     */
    private $filters = [];

    public function __construct(ClassLoader $classLoader)
    {
        $this->classLoader = $classLoader;
    }

    public function namespace(string $namespace): ClassFinderInterface
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function implements($interfaces): ClassFinderInterface
    {
        $implementation = [];
        if (is_array($interfaces)) {
            $implementation = array_merge($implementation, $interfaces);
        }
        $this->filter(new InterfacesFilter($implementation));
        return $this;
    }

    public function traits($traits): ClassFinderInterface
    {
        $traitsRequired = [];

        if (is_array($traits)) {
            $traitsRequired = $traits;
        } elseif (is_string($traits)) {
            $traitsRequired[] = $traits;
        }
        $this->filter(new TraitsFilter($traitsRequired));
        return $this;
    }

    public function extends(string $className): ClassFinderInterface
    {
        $this->filter(new ExtendFilter($className));
        return $this;
    }

    /**
     * @param Closure|Filter $filter
     * @return $this|ClassFinderInterface
     */
    public function filter($filter): ClassFinderInterface
    {
        if ($filter instanceof Filter) {
            $closure = Closure::fromCallable($filter);
        } elseif ($filter instanceof Closure) {
            $closure = $filter;
        }
        if (isset($closure)) {
            array_push($this->filters, $closure);
        }
        return $this;
    }

    public function search(): array
    {
        $this->results = [];
        if (is_null($this->namespace)) {
            throw new NamespaceRequiredException();
        }
        $this->searchPsr4($this->namespace);
        return $this->applyFilter($this->results, $this->filters);
    }
    private function searchPsr4(string $namespace)
    {
        $psr4 = $this->classLoader->getPrefixesPsr4();
        foreach ($psr4 as $declaredNamespace => $paths) {

        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->search());
    }
}