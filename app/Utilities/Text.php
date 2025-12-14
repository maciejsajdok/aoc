<?php

declare(strict_types=1);

namespace App\Utilities;

use function preg_quote;
use function preg_split;

class Text
{
    public static function explode(string $text, string $delimiter): array
    {
        $pattern = '/['. preg_quote($delimiter) .']/';
        return preg_split($pattern, $text);
    }
}
