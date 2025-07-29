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

    const ORIGIN_DESTINATION_OPTIONS = [
        'Tec Rec' => 'Tec Rec',
        'Sodira La pola' => 'Sodira La pola',
        'Sodira El puente' => 'Sodira El puente',
        'Sodira Gravera Roman M506' => 'Sodira Gravera Roman M506',
        'Eiffage' => 'Eiffage',
        'Recicam' => 'Recicam',
        'Salmedina' => 'Salmedina',
        'Alameda de la Sagra Onegescor' => 'Alameda de la Sagra Onegescor',
        'Msc aridos' => 'Msc aridos',
        'Hanson' => 'Hanson',
        'Tramsa' => 'Tramsa',
        'Mahorsa' => 'Mahorsa',
        'Otro' => 'Otro',
    ];

    const DESTINATION_OPTIONS = [
        'ALDEHUELA AMAEXCO' => 'ALDEHUELA AMAEXCO',
        'TRUSAN' => 'TRUSAN',
        'RECICAM' => 'RECICAM',
        'SALMEDINA VALDEMINGOMEZ' => 'SALMEDINA VALDEMINGOMEZ',
        'LAREDO CEMEX' => 'LAREDO CEMEX',
        'LAREDO SODIRA M506' => 'LAREDO SODIRA M506',
        'LAREDO EIFFAGE' => 'LAREDO EIFFAGE',
        'LAREDO SALITRAL' => 'LAREDO SALITRAL',
        'ALAMEDA DE LA SAGRA ONEGESCOR ' => 'ALAMEDA DE LA SAGRA ONEGESCOR ',
        'LOS CANTILLOS FGM' => 'LOS CANTILLOS FGM',
        'ARIDOS LA CABEZA (EL ALAMO)' => 'ARIDOS LA CABEZA (EL ALAMO)',
        'GADARAI' => 'GADARAI',
        'TRAMSA' => 'TRAMSA',
        'SODIRA LA POLA' => 'SODIRA LA POLA',
        'CCR LAS MULAS' => 'CCR LAS MULAS',
        'TEC REC' => 'TEC REC',
        'ARIDENCA (MSC ARIDOS 2002)' => 'ARIDENCA (MSC ARIDOS 2002)',
        'OTRO' => 'OTRO',
    ];

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

    /** @var Document */
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

    public function getDocument(): ?Document
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

    public function isProviderSigned(): bool
    {
        return boolval($this->provider_signed);
    }

    public function setProviderSigned(bool $provider_signed): void
    {
        $this->provider_signed = $provider_signed;
    }

    /**
     * @return float
     */
    public function getSpoonHours(): ?float
    {
        return $this->spoon_hours;
    }

    /**
     * @param float $spoon_hours
     */
    public function setSpoonHours(float $spoon_hours): void
    {
        $this->spoon_hours = $spoon_hours;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType(),
            'site' => [
                'name' => $this->getSite()->getName(),
                'id' => $this->getSite()->getId(),
            ],
            'employee' => $this->getEmployee()->getName(),
            'machine' => [
                'id' => $this->getMachine() ? $this->getMachine()->getId() : null,
                'name' => $this->getMachine() ? $this->getMachine()->getName() : null,
            ],
            'date' => $this->date,
            'hours' => $this->hours,
            'hammer_hours' => $this->hammer_hours,
            'num_travels' => $this->num_travels,
            'material' => $this->material ? $this->material->getName() : null,
            'tons' => $this->tons,
            'portages' => $this->portages,
            'provider' => $this->provider,
            'comments' => $this->comments,
            'liters' => $this->liters
        ];
    }

}
