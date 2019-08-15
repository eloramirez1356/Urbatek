<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
class Ticket
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /** @var Site */
    private $site;

    /**
     * @var \DateTime
     */
    private $date;

    /** @var int */
    private $hours;

    /** @var int */
    private $num_travels;

    /** @var Material */
    private $material;

    private $document;


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


    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setSite(Site $site): void
    {
        $this->site = $site;
    }

    public function getSite(): Site
    {
        return $this->site;
    }

    public function setMaterial(Material $material): void
    {
        $this->material = $material;
    }
    public function getMaterial(): Material
    {
        return $this->material;
    }

    public function setHours(int $hours): void
    {
        $this->hours = $hours;
    }

    public function getHours(): int
    {
        return $this->hours;
    }

    public function setNumTravels(int $num_travels): void
    {
        $this->num_travels = $num_travels;
    }

    public function getNumTravels(): int
    {
        return $this->num_travels;
    }

    public function getDocument()
    {
        return $this->document;
    }

    public function setDocument($document): void
    {
        $this->document = $document;
    }
    
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
