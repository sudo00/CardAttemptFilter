<?php

declare(strict_types=1);

namespace App\Filter;

use App\Filter\Exception\NoChildFilterException;

abstract class AbstractFilter implements FilterInterface
{
    public function __construct(
        protected ?FilterInterface $childFilter = null,
    ) {
    }

    public function getFilterName(): string
    {
        return static::FILTER_NAME;
    }

    /**
     * @throws NoChildFilterException
     */
    public function getChildFilter(): FilterInterface
    {
        return $this->childFilter ?: throw new NoChildFilterException('No child filter');
    }

    public function sort(array $data): array
    {
        usort($data, function ($item1, $item2) {
            return $item2[$this->getFilterName()] <=> $item1[$this->getFilterName()];
        });

        return $data;
    }
}
