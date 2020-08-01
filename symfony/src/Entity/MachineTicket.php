<?php

namespace App\Entity;

class MachineTicket extends Ticket
{
    /** @var int */
    protected $hours;

    /** @var int */
    protected $hammer_hours;

    public function __construct(
        \DateTime $date,
        Site $site,
        Employee $employee,
        Machine $machine,
        int $hours = null,
        $hammer_hours = null,
        $comments
    ) {
        $this->type = self::TYPE_MACHINE;
        $this->date = $date;
        $this->site = $site;
        $this->employee = $employee;
        $this->machine = $machine;
        $this->hours = $hours;
        $this->hammer_hours = $hammer_hours;
        $this->comments = $comments;
    }

    public function getMaterial(): ?Material
    {
        return $this->material;
    }

    public function setMaterial(Material $material): void
    {
        $this->material = $material;
    }

    public function setHours(int $hours): void
    {
        $this->hours = $hours;
    }

    public function getHours()
    {
        return $this->hours;
    }

    public function getHammerHours()
    {
        return $this->hammer_hours;
    }

    public function setHammerHours($hammer_hours): void
    {
        $this->hammer_hours = $hammer_hours;
    }
}
