<?php

declare(strict_types=1);

namespace App\Services\Aoc;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;
use function config;

class AocProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(AocTaskFetcher::class)
            ->needs(ClientInterface::class)
            ->give(function (){
                $userName = config('aoc.github.username');
                $repository = config('aoc.github.repository');
                $email = config('aoc.github.email');
                $userAgent = "github.com/{$userName}/{$repository} by {$email}";
                $sessionCookie = config('aoc.session_cookie');
                $baseUrl = config('aoc.url');

                return new Client([
                        'base_uri' => $baseUrl,
                        'headers' => [
                            'User-Agent' => $userAgent,
                            'Cookie' => "session={$sessionCookie}",
                        ]
                    ]
                );
            });

    }
}
