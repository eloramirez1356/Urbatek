<?php

namespace App\Entity;

class Material
{
    const TYPE_SUPPLY = 'supply';
    const TYPE_WITHDRAWAL = 'withdrawal';

    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var float **/
    private $price;

    /** @var string */
    private $type;

    public function __construct($name, $price, $type)
    {
        $this->name = $name;
        $this->price = $price;
        $this->type = $type;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function isSupply()
    {
        return $this->type == self::TYPE_SUPPLY;
    }

    public function isWithdrawal()
    {
        return $this->type == self::TYPE_WITHDRAWAL;
    }
}
