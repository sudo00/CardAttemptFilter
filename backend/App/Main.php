<?php

declare(strict_types=1);

namespace App;

use App\Filter\Attempt1Filter;
use App\Filter\Attempt2Filter;
use App\Filter\Attempt3Filter;
use App\Filter\Attempt4Filter;
use App\Filter\FilterInterface;
use App\Filter\Exception\NoChildFilterException;
use App\Filter\PilotNameFilter;
use App\Filter\TotalResultFilter;

final class Main
{
    public function run(): void
    {
        $dataCars = $this->getDataFromFile(__DIR__ . '/../files/data_cars.json');
        $dataAttempts = $this->getDataFromFile(__DIR__ . '/../files/data_attempts.json');
        $formalizedData = $this->formalizeDataFromFiles($dataCars, $dataAttempts);

        $pilotNameFilter = new PilotNameFilter();
        $attempt1Filter = new Attempt1Filter($pilotNameFilter);
        $attempt2Filter = new Attempt2Filter($attempt1Filter);
        $attempt3Filter = new Attempt3Filter($attempt2Filter);
        $attempt4Filter = new Attempt4Filter($attempt3Filter);
        $totalResultFilter = new TotalResultFilter($attempt4Filter);

        $result = $this->sortByFilter($formalizedData, $totalResultFilter);

        if (isset($_POST['saveResult'])) {
            $this->saveResult($result);
        }

        include __DIR__ . '/View/table.php';
    }

    private function sortByFilter(array $pilotsRows, FilterInterface $filter): array
    {
        $result = [];
        $countOfSameValue = 0;
        $pilotsRows = array_values($filter->sort($pilotsRows));

        foreach ($pilotsRows as $indexOfRows => $pilot) {
            if ($indexOfRows != $countOfSameValue) {
                continue;
            }

            $countOfSameValue = array_count_values(array_column($pilotsRows, $filter->getFilterName()))[$pilot[$filter->getFilterName()]];

            if ($countOfSameValue > 1) {
                $filteredPilots = [];
                for ($i = $indexOfRows; $i < $indexOfRows + $countOfSameValue; $i++) {
                    $filteredPilots[] = $pilotsRows[$i];
                }

                try {
                    $filteredPilots = $this->sortByFilter($filteredPilots, $filter->getChildFilter());
                } catch (NoChildFilterException) {
                }

                foreach ($filteredPilots as $filteredPilot) {
                    $result[] = $filteredPilot;
                }
            } else {
                $result[] = $pilot;
            }

            $countOfSameValue += $indexOfRows;
        }

        return $result;
    }

    private function formalizeDataFromFiles(array $dataCars, array $dataAttempts): array
    {
        $totalData = [];

        foreach ($dataCars['data'] as $dataCar) {
            $data = [
                'pilotName' => $dataCar['name'],
                'pilotCity' => $dataCar['city'],
                'pilotCar' => $dataCar['car'],
                'attempt1' => 0,
                'attempt2' => 0,
                'attempt3' => 0,
                'attempt4' => 0,
                'totalResult' => 0,
            ];

            $attemptNumber = 0;
            foreach ($dataAttempts['data'] as $attempt) {
                if ($attempt['id'] === $dataCar['id']) {
                    $data['attempt' . $attemptNumber + 1] = $attempt['result'];
                    $data['totalResult'] += $attempt['result'];
                    $attemptNumber++;
                }

                if ($attemptNumber === 4) {
                    break;
                }
            }

            $totalData[] = $data;
        }

        return $totalData;
    }

    private function getDataFromFile(string $fileName): array
    {
        $str = file_get_contents($fileName);

        return json_decode($str, true);
    }

    private function saveResult(array $pilotsRows): void
    {
        $buffer = fopen(__DIR__ . '/../files/results.csv', 'w');

        fputs($buffer, chr(0xEF) . chr(0xBB) . chr(0xBF));

        foreach($pilotsRows as $val) {
            fputcsv($buffer, $val, ';');
        }

        fclose($buffer);
    }
}
