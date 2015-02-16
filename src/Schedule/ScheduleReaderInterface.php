<?php
namespace Icecave\Siphon\Schedule;

interface ScheduleReaderInterface
{
    public function read($sport, $league);
}
