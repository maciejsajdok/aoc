<?php

declare(strict_types=1);

namespace App\Services\Aoc;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\ClientInterface;

class AocTaskFetcher
{
    public function __construct(
        private ClientInterface $client
    )
    {

    }


    /**
     * @throws GuzzleException
     */
    public function getInput(int $day, int $year): string
    {
        $inputUrl = "{$year}/day/{$day}/input";
        $response = $this->client->request('GET', $inputUrl);
        return $response->getBody()->getContents();
    }

    public function getContent(int $day, int $year): string
    {
        $contentUrl = "{$year}/day/{$day}";
        $response = $this->client->request('GET', $contentUrl);
        return $response->getBody()->getContents();
    }
}
