<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="symfony_demo_tag")
 *
 * Defines the properties of the Tag entity to represent the post tags.
 *
 * See https://symfony.com/doc/current/book/doctrine.html#creating-an-entity-class
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class Employee
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $surname;

    /** @var User */
    private $user;

    /** @var Site[] */
    private $sites;

    /** @var Machine[] */
    private $machines;


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

    /**
     * @return Machine[]
     */
    public function getMachines()
    {
        return $this->machines;
    }

    public function setMachines(array $machines): void
    {
        $this->machines = $machines;
    }

    /**
     * @return Site[]
     */
    public function getSites()
    {
        return $this->sites;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getFullName()
    {
        return $this->name . ' ' . $this->surname;
    }
}
