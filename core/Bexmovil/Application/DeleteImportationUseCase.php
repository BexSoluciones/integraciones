<?php

declare(strict_types=1);

namespace Core\Bexmovil\Application;

use Core\Bexmovil\Importation\Domain\Contracts\ImportationRepositoryContract;
use Core\Bexmovil\Importation\Domain\ValueObjects\ImportationId;

final class DeleteImportationUseCase
{
    private $repository;

    public function __construct(ImportationRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $ImportationId): void
    {
        $id = new ImportationId($ImportationId);

        $this->repository->delete($id);
    }
}