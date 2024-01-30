<?php

declare(strict_types=1);

namespace Core\Bexmovil\Infrastructure;

use Illuminate\Http\Request;
use Core\Bexmovil\Application\GetImportationUseCase;
use Core\Bexmovil\Application\UpdateImportationUseCase;
use Core\Bexmovil\Infrastructure\Repositories\EloquentImportationRepository;

final class UpdateUserController
{
    private $repository;

    public function __construct(EloquentImportationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Request $request)
    {
        $importationId = (int)$request->id;

        $getImportationUseCase = new GetImportationUseCase($this->repository);
        $importation           = $getImportationUseCase->__invoke($importationId);

        $userName              = $request->input('name') ?? $user->name()->value();
        $userEmail             = $request->input('email') ?? $user->email()->value();
        $userEmailVerifiedDate = $user->emailVerifiedDate()->value();
        $userPassword          = $user->password()->value();
        $userRememberToken     = $user->rememberToken()->value();

        $updateUserUseCase = new UpdateUserUseCase($this->repository);
        $updateUserUseCase->__invoke(
            $userId,
            $userName,
            $userEmail,
            $userEmailVerifiedDate,
            $userPassword,
            $userRememberToken
        );

        $updatedUser = $getUserUseCase->__invoke($userId);

        return $updatedUser;
    }
}