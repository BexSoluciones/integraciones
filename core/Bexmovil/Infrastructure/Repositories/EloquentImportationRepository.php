<?php

declare(strict_types=1);

namespace Core\Bexmovil\User\Infrastructure\Repositories;

use App\User as EloquentImportationModel;
use Core\Bexmovil\Domain\Contracts\ImportationRepositoryContract;
use Core\Bexmovil\Domain\Importation;
use Core\Bexmovil\Domain\ValueObjects\ImportationId;
use Core\Bexmovil\Domain\ValueObjects\ImportationNameDb;
use Core\Bexmovil\Domain\ValueObjects\ImportationArea;
use Core\Bexmovil\Domain\ValueObjects\ImportationDate;
use Core\Bexmovil\Domain\ValueObjects\ImportationHour;

final class EloquentImportationRepository implements ImportationRepositoryContract
{
    private $eloquentImportationModel;

    public function __construct()
    {
        $this->eloquentImportationModel = new EloquentImportationModel;
    }

    public function find(ImportationId $id): ?Importation
    {
        $Importation = $this->eloquentImportationModel->findOrFail($id->value());

        // Return Domain User model
        return new Importation(
            new ImportationNameDb($Importation->name_db),
            new ImportationArea($Importation->area),
            new ImportationDate($Importation->date),
            new ImportationHour($Importation->hour),
        );
    }

    public function findByCriteria(ImportationNameDb $name_db, ImportationArea $area): ?Importation
    {
        $user = $this->eloquentUserModel
            ->where('name_db', $name->value())
            ->where('area', $area->value())
            ->firstOrFail();

        // Return Domain Importation model
        return new Importation(
            new ImportationNameDb($user->name),
            new ImportationArea($user->email),
            new ImportationDate($user->email_verified_at),
            new ImportationHour($user->password),
        );
    }

    public function save(Importation $importation): void
    {
        $newImportation = $this->eloquentImportationModel;

        $data = [
            'name_db'    => $user->name_db()->value(),
            'area'       => $user->area()->value(),
            'date'       => $user->date()->value(),
            'hour'       => $user->hour()->value(),
        ];

        $newImportation->create($data);
    }

    public function update(UserId $id, Importation $importation): void
    {
        $importationToUpdate = $this->eloquentImportationModel;

        $data = [
            'name_db'  => $user->name_db()->value(),
            'area'     => $user->area()->value(),
        ];

        $importationToUpdate
            ->findOrFail($id->value())
            ->update($data);
    }

    public function delete(ImportationId $id): void
    {
        $this->eloquentUserModel
            ->findOrFail($id->value())
            ->delete();
    }
}