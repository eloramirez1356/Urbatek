<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class Ticket
{
    const TYPE_MACHINE = 'machine';
    const TYPE_TRUCK_HOURS = 'truck_hours';
    const TYPE_TRUCK_MATERIAL = 'truck_material';
    const TYPE_TRUCK_SUPPLY = 'truck_supply';
    const TYPE_TRUCK_WITHDRAWAL = 'truck_withdrawal';
    const TYPE_TRUCK_PORT = 'truck_port';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @var string */
    protected $type;

    /** @var Site */
    protected $site;

    /** @var Employee */
    protected $employee;

    /** @var Machine */
    protected $machine;

    /** @var \DateTime */
    protected $date;

    /** @var int */
    protected $hours;

    /** @var int */
    protected $hammer_hours;

    /** @var int */
    protected $num_travels;

    /** @var Material */
    protected $material;

    /** @var string */
    protected $document;

    /** @var float */
    protected $tons;

    /** @var int */
    protected $portages;

    /** @var string */
    protected $provider;

    /** @var string */
    protected $comments;

    /** @var float */
    protected $liters;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function setSite(Site $site): void
    {
        $this->site = $site;
    }

    public function getSite(): Site
    {
        return $this->site;
    }

    public function getDocument()
    {
        return $this->document;
    }

    public function setDocument($document): void
    {
        $this->document = $document;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function setEmployee(Employee $employee): void
    {
        $this->employee = $employee;
    }

    public function getMachine(): Machine
    {
        return $this->machine;
    }

    public function setMachine(Machine $machine): void
    {
        $this->machine = $machine;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(string $comments = null): void
    {
        $this->comments = $comments;
    }

    /**
     * @return int
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * @param int $hours
     */
    public function setHours(int $hours): void
    {
        $this->hours = $hours;
    }

    public function getLiters(): ?float
    {
        return $this->liters;
    }

    public function setLiters(float $liters): void
    {
        $this->liters = $liters;
    }
}
