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

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\DateType;
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
class Site
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean")
     */
    private $is_active;

    /*** @var DateType **/
    protected $created_at;

    /** @var ArrayCollection */
    private $employees;

    public function __construct($name, $is_active = true)
    {
        $this->name = $name;
        $this->created_at = new \DateTimeImmutable();
        $this->is_active = $is_active;
        $this->employees = new ArrayCollection();
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

    public function isActive()
    {
        return $this->is_active;
    }

    public function activate()
    {
        $this->is_active = true;
    }

    public function getCreatedAt(): ?DateType
    {
        return $this->created_at;
    }

    public function finish()
    {
        $this->is_active = false;
    }

    public function getEmployees()
    {
        return $this->employees->toArray();
    }

    public function addEmployee(Employee $employee)
    {
        $this->employees->add($employee);
    }

    public function setEmployees($employees)
    {
        $this->employees = $employees;
    }

    public function setIsActive($value)
    {
        $this->is_active = $value;
    }
}
