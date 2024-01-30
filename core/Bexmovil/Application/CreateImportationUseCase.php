<?php

declare(strict_types=1);

namespace Core\Bexmovil\Application;

use DateTime;
use Core\Bexmovil\Domain\Contracts\CommandRepositoryContract;
use Core\Bexmovil\Domain\Command;
use Core\Bexmovil\Domain\ValueObjects\CommandNameDb;
use Core\Bexmovil\Domain\ValueObjects\CommandArea;
use Core\Bexmovil\Domain\ValueObjects\CommandDate;
use Core\Bexmovil\Domain\ValueObjects\CommandHour;

final class CreateCommandUseCase
{
    private $repository;

    public function __construct(CommandRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(
        string $name_db,
        string $area,
        ?DateTime $date,
        ?DateTime $hour,
    ): void
    {
        $name_db     = new CommandNameDb($name_db);
        $area        = new CommandArea($area);
        $date        = new CommandDate($date);
        $hour        = new CommandHour($hour);

        $command = Command::create($name_db, $area, $date, $hour);

        $this->repository->save($command);
    }
}