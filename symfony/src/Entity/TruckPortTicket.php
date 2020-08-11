<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class TruckPortTicket extends Ticket
{
    /** @var int */
    protected $portages;

    public function __construct(
        \DateTime $date,
        Site $site,
        Employee $employee,
        Machine $machine,
        int $portages,
        $hours,
        $comments,
        $liters
    ) {
        $this->type = self::TYPE_TRUCK_PORT;
        $this->date = $date;
        $this->site = $site;
        $this->employee = $employee;
        $this->machine = $machine;
        $this->portages = $portages;
        $this->hours = $hours;
        $this->comments = $comments;
        $this->liters = $liters;
    }

    public function getPortages()
    {
        return $this->portages;
    }

    public function setPortages(int $portages): void
    {
        $this->portages = $portages;
    }
}
