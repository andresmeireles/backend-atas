<?php

declare(strict_types=1);

namespace App\Error;

interface Error
{
    public function message(): string;
}
