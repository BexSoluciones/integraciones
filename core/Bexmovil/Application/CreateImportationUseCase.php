<?php

declare(strict_types=1);

namespace Core\Bexmovil\Application;

use DateTime;
use Core\Bexmovil\Domain\Contracts\ImportationRepositoryContract;
use Core\Bexmovil\Domain\Importation;
use Core\Bexmovil\Domain\ValueObjects\ImportationNameDb;
use Core\Bexmovil\Domain\ValueObjects\ImportationArea;
use Core\Bexmovil\Domain\ValueObjects\ImportationDate;
use Core\Bexmovil\Domain\ValueObjects\ImportationHour;

final class CreateImportationUseCase
{
    private $repository;

    public function __construct(ImportationRepositoryContract $repository)
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
        $name_db     = new ImportationNameDb($name_db);
        $area        = new ImportationArea($area);
        $date        = new ImportationDate($date);
        $hour        = new ImportationHour($hour);

        $Importation = Importation::create($name_db, $area, $date, $hour);

        $this->repository->save($Importation);
    }
}