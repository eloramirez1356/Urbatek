<?php

namespace App\Entity;

class TruckMaterialTicket extends Ticket
{
    /** @var int */
    protected $num_travels;

    /** @var Material */
    protected $material;

    /** @var string */
    protected $document;

    /** @var int */
    protected $tons;

    /** @var string */
    protected $provider;

    public function __construct(
        \DateTime $date,
        Site $site,
        Employee $employee,
        Machine $machine,
        int $num_travels = null,
        $hours,
        $comments,
        Material $material = null,
        int $tons = null,
        $provider = null
    ) {
        $this->type = self::TYPE_TRUCK_MATERIAL;
        $this->date = $date;
        $this->site = $site;
        $this->employee = $employee;
        $this->machine = $machine;
        $this->material = $material;
        $this->tons = $tons;
        $this->provider = $provider;
        $this->num_travels = $num_travels;
        $this->comments = $comments;
        $this->hours = $hours;
    }

    public function setMaterial(Material $material): void
    {
        $this->material = $material;
    }
    public function getMaterial(): ?Material
    {
        return $this->material;
    }


    public function setNumTravels(int $num_travels): void
    {
        $this->num_travels = $num_travels;
    }

    public function getNumTravels()
    {
        return $this->num_travels;
    }

    public function getTons()
    {
        return $this->tons;
    }

    public function setTons(float $tons): void
    {
        $this->tons = $tons;
    }

    public function getPortages()
    {
        return $this->portages;
    }

    public function setPortages(int $portages): void
    {
        $this->portages = $portages;
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function setProvider(string $provider): void
    {
        $this->provider = $provider;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
