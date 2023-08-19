<?php

declare(strict_types=1);

namespace App\Service\Meet;

trait NameFormat
{
    public function format(string $text): string
    {
        $n = str_replace(' ', '_', $text);
        $n = trim($n);
        return strtolower($n);
    }
}
