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
        $this->searchPsr($this->namespace);
        return $this->applyFilter($this->results, $this->filters);
    }

    private function searchPsr(string $namespace)
    {
        $prefixes = array_merge(
            $this->classLoader->getPrefixesPsr4(),
            $this->classLoader->getPrefixes()
        );
        foreach ($prefixes as $declaredNamespace => $paths) {
            $declaredNamespace = rtrim($declaredNamespace, "\\");
            if ($declaredNamespace === $namespace) {
                // Case: Declared namespace is equals to searched.
                $this->readFilesFrom($paths, $namespace);
            }  elseif (str_contains($namespace, $declaredNamespace)) {
                // Last Case: declared namespaces is substring of searched namespace.
                // Trim namespace
                $ns = ltrim(str_replace($declaredNamespace, "", $namespace), "\\");
                // Convert namespace into path
                $subdirectory = str_replace("\\", DIRECTORY_SEPARATOR, $ns);
                $updatedPaths = array_map(function ($path) use ($subdirectory) {
                    return $path . DIRECTORY_SEPARATOR . $subdirectory;
                }, $paths);

                $this->readFilesFrom($updatedPaths, $namespace);
            }
        }
    }
    private function readFilesFrom(array $paths, string $namespace)
    {
        foreach ($paths as $path) {
            $dirIterator = new \DirectoryIterator($path);
            foreach ($dirIterator as $splFile) {
                if ($splFile->isFile()) {
                    if ($splFile->getExtension() === "php") {
                        $this->results[] = $namespace . "\\" . $splFile->getBasename(".php");
                    }
                }
                if ($splFile->isDir() && !$splFile->isDot()) {
                    $this->readFilesFrom(
                        array(realpath($splFile->getRealPath())),
                        $namespace . "\\" . $splFile->getBasename()
                    );
                }
            }
        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->search());
    }
}