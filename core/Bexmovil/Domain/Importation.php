<?php

declare(strict_types=1);

namespace Core\Bexmovil\Domain;

use Core\Bexmovil\Domain\ValueObjects\ImportationNameDb;
use Core\Bexmovil\Domain\ValueObjects\ImportationArea;
use Core\Bexmovil\Domain\ValueObjects\ImportationDate;
use Core\Bexmovil\Domain\ValueObjects\ImportationHour;

final class Importation
{
    private $name_db;
    private $area;
    private $date;
    private $hour;

    public function __construct(
        ImportationNameDb $name_db,
        ImportationArea $area,
        ImportationDate $date,
        ImportationHour $hour,
    )
    {
        $this->name_db   = $name_db;
        $this->area      = $area;
        $this->date      = $date;
        $this->hour      = $hour;
    }

    public function name_db(): ImportationNameDb
    {
        return $this->name_db;
    }

    public function area(): ImportationArea
    {
        return $this->area;
    }

    public function date(): ImportationDate
    {
        return $this->date;
    }

    public function hour(): ImportationHour
    {
        return $this->hour;
    }

    public static function create(
        ImportationNameDb $name_db,
        ImportationArea $area,
        ImportationDate $date,
        ImportationHour $hour,
    ): Importation
    {
        $Importation = new self($name_db, $area, $date, $area);
        return $Importation;
    }
}