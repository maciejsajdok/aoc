<?php

return [
    'class_name_format' => 'AocSolution{year}{month}',
    'class_namespace' => 'App\\Solutions\\Aoc\\{year}\\{month}',

    'url' => 'https://adventofcode.com',

    'github' => [
        'username' => env('AOC_GITHUB_USERNAME'),
        'repository' => env('AOC_GITHUB_REPOSITORY'),
        'email' => env('AOC_GITHUB_EMAIL'),
    ],

    'session_cookie' => env('AOC_SESSION_COOKIE'),

];
