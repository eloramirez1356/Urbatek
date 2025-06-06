<?php

namespace App\Entity;

class CreateTicketRequest
{
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

    /** @var float */
    protected $spoon_hours;

    /** @var bool */
    protected $provider_signed;

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

    public function getType(): string
    {
        return $this->type;
    }

    public function getPortages(): int
    {
        return $this->portages;
    }

    public function getTons(): ?float
    {
        return $this->tons;
    }

    public function getHammerHours(): ?int
    {
        return $this->hammer_hours;
    }

    public function getHours(): ?int
    {
        return $this->hours;
    }

    public function getMaterial(): Material
    {
        return $this->material;
    }

    public function getNumTravels(): int
    {
        return $this->num_travels;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function setPortages(int $portages): void
    {
        $this->portages = $portages;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setProvider(string $provider): void
    {
        $this->provider = $provider;
    }

    public function setTons(float $tons): void
    {
        $this->tons = $tons;
    }

    public function setHammerHours(int $hammer_hours): void
    {
        $this->hammer_hours = $hammer_hours;
    }

    public function setHours(int $hours): void
    {
        $this->hours = $hours;
    }

    public function setMaterial(Material $material): void
    {
        $this->material = $material;
    }

    public function setNumTravels(int $num_travels): void
    {
        $this->num_travels = $num_travels;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(string $comments= null): void
    {
        $this->comments = $comments;
    }

    public function getLiters(): ?float
    {
        return $this->liters;
    }

    public function setLiters(float $liters): void
    {
        $this->liters = $liters;
    }

    public function getSpoonHours(): ?float
    {
        return $this->spoon_hours;
    }

    public function setSpoonHours(float $spoon_hours): void
    {
        $this->spoon_hours = $spoon_hours;
    }

    public function isProviderSigned()
    {
        return boolval($this->provider_signed);
    }

    public function setProviderSigned(bool $provider_signed): void
    {
        $this->provider_signed = $provider_signed;
    }
}
