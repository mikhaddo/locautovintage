<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VehicleRepository")
 */
class Vehicle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $year_produced;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $engine_type;

    /**
     * @ORM\Column(type="smallint")
     */
    private $engine_displacement;

    /**
     * @ORM\Column(type="smallint")
     */
    private $engine_power;

    /**
     * @ORM\Column(type="smallint")
     */
    private $max_speed;

    /**
     * @ORM\Column(type="smallint")
     */
    private $max_seats;

    /**
     * @ORM\Column(type="array")
     */
    private $pictures = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="vehicles")
     */
    private $owner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getYearProduced(): ?string
    {
        return $this->year_produced;
    }

    public function setYearProduced(string $year_produced): self
    {
        $this->year_produced = $year_produced;

        return $this;
    }

    public function getEngineType(): ?string
    {
        return $this->engine_type;
    }

    public function setEngineType(string $engine_type): self
    {
        $this->engine_type = $engine_type;

        return $this;
    }

    public function getEngineDisplacement(): ?int
    {
        return $this->engine_displacement;
    }

    public function setEngineDisplacement(int $engine_displacement): self
    {
        $this->engine_displacement = $engine_displacement;

        return $this;
    }

    public function getEnginePower(): ?int
    {
        return $this->engine_power;
    }

    public function setEnginePower(int $engine_power): self
    {
        $this->engine_power = $engine_power;

        return $this;
    }

    public function getMaxSpeed(): ?int
    {
        return $this->max_speed;
    }

    public function setMaxSpeed(int $max_speed): self
    {
        $this->max_speed = $max_speed;

        return $this;
    }

    public function getMaxSeats(): ?int
    {
        return $this->max_seats;
    }

    public function setMaxSeats(int $max_seats): self
    {
        $this->max_seats = $max_seats;

        return $this;
    }

    public function getPictures(): ?array
    {
        return $this->pictures;
    }

    public function setPictures(array $pictures): self
    {
        $this->pictures = $pictures;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
