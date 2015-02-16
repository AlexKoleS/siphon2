<?php
namespace Icecave\Sid\Schedule;

use Icecave\Chrono\Date;

class Season
{
    public function __construct(
        $id,
        $name,
        Date $startDate,
        Date $endDate
    ) {
        $this->id           = $id;
        $this->name         = $name;
        $this->startDate    = $startDate;
        $this->endDate      = $endDate;
        $this->competitions = [];
    }

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }

    public function startDate()
    {
        return $this->startDate;
    }

    public function endDate()
    {
        return $this->endDate;
    }

    public function add(Competition $competition)
    {
        $this->competitions[] = $competition;
    }

    public function competitions()
    {
        return $this->competitions;
    }

    private $id;
    private $name;
    private $startDate;
    private $endDate;
    private $competitions;
}
