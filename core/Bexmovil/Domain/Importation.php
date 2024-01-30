<?php

declare(strict_types=1);

namespace Core\Bexmovil\Domain;

use Core\Bexmovil\Domain\ValueObjects\CommandNameDb;
use Core\Bexmovil\Domain\ValueObjects\CommandArea;
use Core\Bexmovil\Domain\ValueObjects\CommandDate;
use Core\Bexmovil\Domain\ValueObjects\CommandHour;

final class Command
{
    private $name_db;
    private $area;
    private $date;
    private $hour;

    public function __construct(
        CommandNameDb $name_db,
        CommandArea $area,
        CommandDate $date,
        CommandHour $hour,
    )
    {
        $this->name_db   = $name_db;
        $this->area      = $area;
        $this->date      = $date;
        $this->hour      = $hour;
    }

    public function name_db(): CommandNameDb
    {
        return $this->name_db;
    }

    public function area(): CommandArea
    {
        return $this->area;
    }

    public function date(): CommandDate
    {
        return $this->date;
    }

    public function hour(): CommandHour
    {
        return $this->hour;
    }

    public static function create(
        CommandNameDb $name_db,
        CommandArea $area,
        CommandDate $date,
        CommandHour $hour,
    ): Command
    {
        $command = new self($name_db, $area, $date, $area);
        return $command;
    }
}