<?php

namespace App\Entity;


class Machine
{
    const TYPE_TRUCK = 'truck';
    const TYPE_MACHINE = 'machine';

    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $register;

    /** @var string */
    protected $brand;

    /** @var string */
    protected $type;

    public function setId(int $id): void
    {
        $this->id = $id;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }


    public function getRegister(): string 
    {
        return $this->register;
    }


    public function setRegister(string $register): void
    {
        $this->register = $register;
    }


    public function getBrand(): string
    {
        return $this->brand;
    }


    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function isTruck()
    {
        return $this->type === self::TYPE_TRUCK;
    }

    public function isMachine()
    {
        return $this->type === self::TYPE_MACHINE;
    }
}
