<?php

declare(strict_types=1);

namespace Src\BoundedContext\User\Domain\ValueObjects;

use InvalidArgumentException;

final class CommanNameDb
{
    private $value;

    /**
     * CommanNameDb constructor.
     * @param string $name_db
     * @throws InvalidArgumentException
     */
    public function __construct(string $name_db)
    {
        $this->validate($name_db);
        $this->value = $name_db;
    }

    /**
     * @param string $name_db
     * @throws InvalidArgumentException
     */
    private function validate(string $name_db): void
    {
        if (!filter_var($name_db, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                sprintf('<%s> does not allow the invalid email: <%s>.', static::class, $email)
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}