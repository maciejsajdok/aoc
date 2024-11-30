<?php

namespace App\Console\Commands;

use App\Services\Aoc\AocTaskFetcher;
use DOMDocument;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use JetBrains\PhpStorm\NoReturn;
use function app_path;
use function array_search;
use function config;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function libxml_clear_errors;
use function libxml_use_internal_errors;
use function resource_path;
use function sprintf;
use function str_replace;
use function strlen;
use function substr;

class PrepareAocTask extends Command
{
    public const MAX_EXAMPLE_LENGTH = 74;
    protected $signature = 'aoc:prepare {day} {year=2024}';

    protected $description = 'Prepare task';

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

        $challengeUrl = $this->getChallengeUrl();
        $this->info("Challenge url {$challengeUrl}");
        $this->handleInputData($aocTaskFetcher);
        $this->handleContent($aocTaskFetcher);
        $this->handleStub();

    }

    private function saveContents(string $filename, string $contents): void
    {
        $dir = implode('/', explode('/', $filename, -1));
        if (! is_dir($dir)) {
            mkdir($dir);
        }

        file_put_contents($filename, $contents);
    }

    /**
     * @throws GuzzleException
     */
    private function handleInputData(AocTaskFetcher $aocTaskFetcher): void
    {
        $fileName = resource_path("aoc/inputs/{$this->year}/{$this->getFormattedDay()}.txt");

        if (file_exists($fileName)) {
            $this->info("Input already exists at {$fileName}");
        } else {
            $inputData = $aocTaskFetcher->getInput($this->day, $this->year);
            $this->saveContents($fileName, $inputData);
        }
    }

    private function handleContent(AocTaskFetcher $aocTaskFetcher): void
    {
        $fileName = resource_path("aoc/contents/{$this->year}/{$this->getFormattedDay()}.html");

        if (file_exists($fileName)) {
            $this->info("Content already exists at {$fileName}");
        } else {
            $inputData = $aocTaskFetcher->getContent($this->day, $this->year);
            $this->saveContents($fileName, $inputData);
        }

        $this->handleExample($fileName);
    }

    private function handleExample(string $filename): void
    {
        $exampleFilename = resource_path("aoc/inputs/{$this->year}/{$this->getFormattedDay()}.example.txt");

        if (file_exists($exampleFilename)) {
            $this->info("Example file already exists at {$exampleFilename}");
            return;
        }

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTMLFile($filename);
        libxml_clear_errors();
        $codeTags = $dom->getElementsByTagName('code');

        if ($codeTags->length === 0){
            $this->error('Unable to find examples.');
            return;
        }

        $codeTagIndex = 0;
        if ($codeTags->length >1){
            $confirmExampleSelection = $this->confirm('Multiple examples found, would you like to choose one', true);
            if (!$confirmExampleSelection){
                $this->info('Skipping example selection.');
                return;
            }

            $examples = [];

            foreach ($codeTags as $index => $codeTag) {
                $text = str_replace("\n", ' ', $codeTag->textContent);
                if (strlen($text) > self::MAX_EXAMPLE_LENGTH){
                    $trimmed = substr($text, 0, self::MAX_EXAMPLE_LENGTH);
                    $examples[$index] = sprintf('"%s[...+%d chars]"', $trimmed, strlen($text) - strlen($trimmed));
                } else {
                    $examples[$index] = sprintf('"%s"', $text);
                }
            }
            $codeTagIndex = array_search($this->choice('Select example:', $examples), $examples);
        }

        $content = $codeTags->item($codeTagIndex)->textContent;
        $this->saveContents($exampleFilename, $content);
    }

    private function handleStub(): void
    {
        $filename = app_path("Aoc/Year{$this->year}/Solution{$this->getFormattedDay()}.php");
        if (file_exists($filename)) {
            $this->info("File already exists at {$filename}");
        }

        $stub = file_get_contents(app_path('Services/Aoc/Solution.stub'));
        $stub = str_replace('{{year}}', $this->year, $stub);
        $stub = str_replace('{{day}}', $this->getFormattedDay(), $stub);

        $this->saveContents($filename, $stub);
    }


    private function getFormattedDay(): string
    {
        return sprintf('%02d',$this->day);
    }

    private function getChallengeUrl(): string
    {
        $aocUrl = config('aoc.url');
        return "{$aocUrl}/{$this->year}/day/{$this->day}";
    }
}
