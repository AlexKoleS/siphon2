<?php
namespace Icecave\Siphon\Schedule;

class Schedule
{
    public function add(Season $season)
    {
        $this->seasons[] = $season;
    }

    public function seasons()
    {
        return $this->seasons;
    }

    public function competitions()
    {
        $competitions = [];

        foreach ($this->seasons as $season) {
            foreach ($season->competitions() as $competition) {
                $competitions[] = $competition;
            }
        }

        return $competitions;
    }

    private $seasons = [];
}
