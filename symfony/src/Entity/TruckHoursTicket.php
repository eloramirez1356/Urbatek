<?php

namespace App\Entity;

class TruckHoursTicket extends Ticket
{
    /** @var int */
    protected $hours;

    public function __construct(
        \DateTime $date,
        Site $site,
        Employee $employee,
        Machine $machine,
        int $hours = null,
        $liters
    ) {
        $this->type = self::TYPE_TRUCK_HOURS;
        $this->date = $date;
        $this->site = $site;
        $this->employee = $employee;
        $this->machine = $machine;
        $this->hours = $hours;
        $this->liters = $liters;
    }
}
