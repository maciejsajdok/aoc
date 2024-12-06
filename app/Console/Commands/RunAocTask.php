<?php

namespace App\Console\Commands;

use App\Services\Aoc\AocTaskFetcher;
use App\Services\Aoc\SolutionInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use JetBrains\PhpStorm\NoReturn;
use function file_exists;
use function file_get_contents;
use function microtime;
use function resource_path;
use function sprintf;

class RunAocTask extends Command
{
    protected $signature = 'aoc:run {day} {year=2024} {--example}';

    protected $description = 'Run task';

    protected int $day;
    protected int $year;

    /**
     * Execute the console command.
     * @throws GuzzleException
     */
    #[NoReturn] public function handle(AocTaskFetcher $aocTaskFetcher): void
    {
        $this->day = (int) $this->argument('day');
        $this->year = (int) $this->argument('year');

        /** @var SolutionInterface $instance */
        $instance = $this->getSolutionInstance($this->year, $this->day);
        $data = $this->loadInput($this->year, $this->day, $this->option('example'));

        if (!$data) {
            $this->error('No data for this challenge');
            return;
        }

        $start = microtime(true);
        $p1 = $instance->p1($data);
        $stop = microtime(true);
        $elapsed = $stop - $start;
        $this->info(sprintf('Part 1: %s; in: %s s', $p1, sprintf("%.5f", $elapsed)));

        $start = microtime(true);
        $p2 = $instance->p2($data);
        $stop = microtime(true);
        $elapsed = $stop - $start;
        $this->info(sprintf('Part 2: %s; in: %s s', $p2, sprintf("%.5f", $elapsed)));
    }

    private function getSolutionInstance(int $year, int $day)
    {
        $formattedDay = sprintf('%02d', $day);
        $className = "App\\Aoc\\Year{$year}\\Solution{$formattedDay}";

        if (!class_exists($className)) {
            $this->error("{$className} does not exist");
        }

        return new $className();

    }

    private function loadInput(int $year, int $day, bool $example): ?string
    {
        $formattedDay = sprintf('%02d', $day);
        $inputFile = sprintf(resource_path('aoc/inputs/%s/%s%s.txt'), $year, $formattedDay, $example ? '.example' : '');
        if (!file_exists($inputFile)) {
            return null;
        }
        return file_get_contents($inputFile);
    }
}
