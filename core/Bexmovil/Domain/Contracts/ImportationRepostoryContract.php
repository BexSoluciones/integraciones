<?php

declare(strict_types=1);

namespace Core\Bexmovil\Command\Domain\Contracts;

use Core\Bexmovil\Command\Domain\Command;
use Core\Bexmovil\Command\Domain\ValueObjects\CommandEmail;
use Core\Bexmovil\Command\Domain\ValueObjects\CommandEmailVerifiedDate;
use Core\Bexmovil\Command\Domain\ValueObjects\CommandId;
use Core\Bexmovil\Command\Domain\ValueObjects\CommandName;

interface CommandRepositoryContract
{
    public function find(CommandId $id): ?Command;

    public function findByCriteria(CommandName $CommandName, CommandEmail $CommandEmail): ?Command;

    public function save(Command $Command): void;

    public function update(CommandId $CommandId, Command $Command): void;

    public function delete(CommandId $id): void;
}