<?php

namespace App\Entity\College;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="universities")
 */
class University
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
     * @Assert\NotBlank(message="uni.name")
     * @Assert\Length(
     *     min=5,
     *     minMessage="uni.too_short",
     *     max=10000,
     *     maxMessage="uni.too_long"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $uniKey;

    /**
     * @var Student
     *
     * @ORM\OneToOne(targetEntity="Student", mappedBy="university")
     */
    private $student;

    public function __construct()
    {

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

    public function getUniKey(): string
    {
        return $this->getUniKey;
    }

    public function setUniKey(string $key): void 
    {
        $this->uniKey = str_replace([" ", "_", "-"], "_", strtolower($key));
    }


    public function getStudent(): Student 
    {
        return $this->student;
    }

    public function setStudent(Student $student): void 
    {
        $this->student = $student;
    }
}
