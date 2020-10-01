<?php

declare(strict_types=1);

namespace Gooyer\ClassFinder;

use Closure;

trait ApplyFilters
{
    /**
     * @param array<string> $results
     * @param array<Filter> $filters
     * @return array<string>
     */
    protected function filter(array $results, array $filters): array
    {
        $filteredResults = [];

        foreach ($results as $result) {
            $classRef = new \ReflectionClass($result);
            $failed = false;
            foreach ($filters as $filter) {
                $valid = $filter($classRef);
                if ($valid === false) {
                    $failed = true;
                }
            }
            if ($failed === false) {
                array_push($filteredResults, $result);
            }
        }

        return $filteredResults;
    }
}