<?php

declare(strict_types=1);

namespace App\Filter;

final class PilotNameFilter extends AbstractFilter
{
    protected const FILTER_NAME = 'pilotName';

    public function sort(array $data): array
    {
        uasort($data, function($item1, $item2) {
            return strcmp($item1[$this->getFilterName()], $item2[$this->getFilterName()]);
        });

        return $data;
    }
}
