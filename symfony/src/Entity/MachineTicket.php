<?php

namespace App\Entity;

class MachineTicket extends Ticket
{
    /** @var int */
    protected $hours;

    /** @var int */
    protected $hammer_hours;

    /** @var float */
    protected $spoon_hours;

    /** @var bool */
    protected $provider_signed;

    public function __construct(
        \DateTime $date,
        Site $site,
        Employee $employee,
        Machine $machine,
        int $hours = null,
        $hammer_hours = null,
        $comments,
        $liters,
        $spoon_hours,
        $provider_signed
    ) {
        $this->type = self::TYPE_MACHINE;
        $this->date = $date;
        $this->site = $site;
        $this->employee = $employee;
        $this->machine = $machine;
        $this->hours = $hours;
        $this->hammer_hours = $hammer_hours;
        $this->comments = $comments;
        $this->liters = $liters;
        $this->spoon_hours = $spoon_hours;
        $this->provider_signed = $provider_signed;
    }

    public function getMaterial(): ?Material
    {
        return $this->material;
    }

    public function setMaterial(Material $material): void
    {
        $this->material = $material;
    }

    public function getHammerHours()
    {
        return $this->hammer_hours;
    }

    public function setHammerHours($hammer_hours): void
    {
        $this->hammer_hours = $hammer_hours;
    }

    public function getSpoonHours(): ?float
    {
        return $this->spoon_hours;
    }

    public function setSpoonHours(float $spoon_hours): void
    {
        $this->spoon_hours = $spoon_hours;
    }
}
