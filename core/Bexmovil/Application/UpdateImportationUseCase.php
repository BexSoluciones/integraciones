<?php

declare(strict_types=1);

namespace Core\Bexmovil\Application;

use DateTime;
use Core\Bexmovil\Domain\Contracts\ImportationRepositoryContract;
use Core\Bexmovil\Domain\Importation;
use Core\Bexmovil\Domain\ValueObjects\ImportationEmail;
use Core\Bexmovil\Domain\ValueObjects\ImportationEmailVerifiedDate;
use Core\Bexmovil\Domain\ValueObjects\ImportationId;
use Core\Bexmovil\Domain\ValueObjects\ImportationName;
use Core\Bexmovil\Domain\ValueObjects\ImportationPassword;
use Core\Bexmovil\Domain\ValueObjects\ImportationRememberToken;

final class UpdateImportationUseCase
{
    private $repository;

    public function __construct(ImportationRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(
        int $ImportationId,
        string $ImportationNameDb,
        string $ImportationArea,
        ?DateTime $ImportationDate,
        ?DateTime $ImportationHour,
    ): void
    {
        $id         = new ImportationId($ImportationId);
        $name_db    = new ImportationNameDb($ImportationNameDb);
        $area       = new ImportationArea($ImportationArea);
        $date       = new ImportationDate($ImportationDate);
        $hour       = new ImportationHour($ImportationHour);

        $importation = Importation::create($name_db, $area, $date, $hour);

        $this->repository->update($id, $importation);
    }
}