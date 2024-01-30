<?php

declare(strict_types=1);

namespace Core\Bexmovil\User\Infrastructure\Repositories;

use App\User as EloquentCommandModel;
use Core\Bexmovil\Domain\Contracts\CommandRepositoryContract;
use Core\Bexmovil\Domain\Command;
use Core\Bexmovil\Domain\ValueObjects\CommandId;
use Core\Bexmovil\Domain\ValueObjects\CommandNameDb;
use Core\Bexmovil\Domain\ValueObjects\CommandArea;
use Core\Bexmovil\Domain\ValueObjects\CommandDate;
use Core\Bexmovil\Domain\ValueObjects\CommandHour;

final class EloquentCommandRepository implements CommandRepositoryContract
{
    private $eloquentCommandModel;

    public function __construct()
    {
        $this->eloquentCommandModel = new EloquentCommandModel;
    }

    public function find(CommandId $id): ?Command
    {
        $command = $this->eloquentCommandModel->findOrFail($id->value());

        // Return Domain User model
        return new Command(
            new CommandNameDb($command->name_db),
            new CommandArea($command->area),
            new CommandDate($command->date),
            new CommandHour($command->hour),
        );
    }

    public function findByCriteria(UserName $name, UserEmail $email): ?Command
    {
        $user = $this->eloquentUserModel
            ->where('name', $name->value())
            ->where('email', $email->value())
            ->firstOrFail();

        // Return Domain User model
        return new User(
            new UserName($user->name),
            new UserEmail($user->email),
            new UserEmailVerifiedDate($user->email_verified_at),
            new UserPassword($user->password),
            new UserRememberToken($user->remember_token)
        );
    }

    public function save(User $user): void
    {
        $newUser = $this->eloquentUserModel;

        $data = [
            'name'              => $user->name()->value(),
            'email'             => $user->email()->value(),
            'email_verified_at' => $user->emailVerifiedDate()->value(),
            'password'          => $user->password()->value(),
            'remember_token'    => $user->rememberToken()->value(),
        ];

        $newUser->create($data);
    }

    public function update(UserId $id, User $user): void
    {
        $userToUpdate = $this->eloquentUserModel;

        $data = [
            'name'  => $user->name()->value(),
            'email' => $user->email()->value(),
        ];

        $userToUpdate
            ->findOrFail($id->value())
            ->update($data);
    }

    public function delete(UserId $id): void
    {
        $this->eloquentUserModel
            ->findOrFail($id->value())
            ->delete();
    }
}