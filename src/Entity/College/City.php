<?php

namespace App\Entity\College;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="cities")
 */
class City
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
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="city.name")
     * @Assert\Length(
     *     min=5,
     *     minMessage="city.too_short",
     *     max=10000,
     *     maxMessage="city.too_long"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $cityKey;

    /**
     * @var Student
     *
     * @ORM\OneToMany(targetEntity="Student", mappedBy="city")
     */
    private $students;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime;
        $this->updatedAt = new \DateTime;
        $this->students = new ArrayCollection();
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCityKey(): string
    {
        return $this->cityKey;
    }

    public function setCityKey(string $key): void 
    {
        $this->cityKey = str_replace([" ", "_", "-"], "_", strtolower($key));
    }


    public function getStudents(): Student 
    {
        return $this->students;
    }

    public function addStudent(Student $student): void 
    {
        $student->setCity($this);
        if(!$this->students->contains($student)) {
            $this->students->add($student);
        }
    }

    public function removeStudent(Student $student): void 
    {
        $student->setCity(null);
        $this->students->removeElement($student);

    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
