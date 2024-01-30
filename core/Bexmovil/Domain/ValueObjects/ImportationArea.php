<?php

declare(strict_types=1);

namespace Src\BoundedContext\User\Domain\ValueObjects;

final class CommandArea
{
    private $value;

    public function __construct(string $area)
    {
        $this->value = $area;
    }

    public function value(): string
    {
        return $this->value;
    }
}