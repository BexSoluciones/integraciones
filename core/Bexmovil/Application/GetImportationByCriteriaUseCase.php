<?php

declare(strict_types=1);

namespace Core\Bexmovil\Application;

use Core\Bexmovil\Domain\Contracts\UserRepositoryContract;
use Core\Bexmovil\Domain\User;
use Core\Bexmovil\Domain\ValueObjects\UserEmail;
use Core\Bexmovil\Domain\ValueObjects\UserName;

final class GetUserByCriteriaUseCase
{
    private $repository;

    public function __construct(UserRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $userName, string $userEmail): ?User
    {
        $name  = new UserName($userName);
        $email = new UserEmail($userEmail);

        $user = $this->repository->findByCriteria($name, $email);

        return $user;
    }
}