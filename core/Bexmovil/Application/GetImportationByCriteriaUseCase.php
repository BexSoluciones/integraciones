<?php

declare(strict_types=1);

namespace Core\Bexmovil\Application;

use Core\Bexmovil\Domain\Contracts\ImportationRepositoryContract;
use Core\Bexmovil\Domain\Importation;
use Core\Bexmovil\Domain\ValueObjects\ImportationNameDb;
use Core\Bexmovil\Domain\ValueObjects\ImportationArea;

final class GetImportationByCriteriaUseCase
{
    private $repository;

    public function __construct(ImportationRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $ImportationNameDb, string $ImportationArea): ?Importation
    {
        $name_db  = new ImportationNameDb($ImportationName);
        $area = new ImportationArea($ImportationNameDb);

        $importation = $this->repository->findByCriteria($name_db, $area);

        return $importation;
    }
}