<?php

declare(strict_types=1);

namespace Src\BoundedContext\User\Domain\ValueObjects;

use DateTime;

final class ImportationHour
{
    private $value;

    public function __construct(?DateTime $hour)
    {
        $this->value = $hour;
    }

    public function value(): ?DateTime
    {
        return $this->value;
    }
}