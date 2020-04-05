<?php

namespace App\Entity;


class Machine
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $register;

    /** @var string */
    protected $brand;



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





}
