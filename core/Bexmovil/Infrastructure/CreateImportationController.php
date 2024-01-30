<?php

declare(strict_types=1);

namespace Src\BoundedContext\User\Infrastructure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Src\BoundedContext\User\Application\CreateUserUseCase;
use Src\BoundedContext\User\Application\GetUserByCriteriaUseCase;
use Src\BoundedContext\User\Infrastructure\Repositories\EloquentUserRepository;

final class CreateImportationController
{
    private $repository;

    public function __construct(EloquentImportationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Request $request)
    {
        $name_db    = $request->input('name_db');
        $area       = $request->input('area');
        $date       = $request->input('date');
        $hour       = $request->input('hour');

        $createImportationUseCase = new CreateImportationUseCase($this->repository);
        $createImportationUseCase->__invoke(
            $name_db,
            $area,
            $date,
            $hour
        );

        $getImportationByCriteriaUseCase = new GetImportationByCriteriaUseCase($this->repository);
        $newImportation                  = $getImportationByCriteriaUseCase->__invoke($name_db, $area);

        return $newImportation;
    }
}