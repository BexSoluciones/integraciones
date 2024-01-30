<?php

declare(strict_types=1);

namespace Src\BoundedContext\User\Domain\ValueObjects;

use DateTime;

final class ImportationDate
{
    private $value;

    public function __construct(?DateTime $date)
    {
        $this->value = $date;
    }

    public function value(): ?DateTime
    {
        return $this->value;
    }
}