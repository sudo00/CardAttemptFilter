<?php

declare(strict_types=1);

namespace App\Filter;

interface FilterInterface
{
    public function getFilterName(): string;

    public function getChildFilter(): FilterInterface;

    public function sort(array $data): array;
}
