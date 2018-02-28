<?php

namespace App\Entity\College;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="courses")
 */
class Course
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
    private $courseKey;

    /**
     * @var Student
     *
     * Many Courses have Many Students.
     * @ORM\ManyToMany(targetEntity="Student", mappedBy="courses")
     */
    private $students;

    /**
     * @var Grade[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Grade", mappedBy="course", cascade={"persist", "remove"}, orphanRemoval=TRUE)
     */
    private $grades;

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
        $this->grades = new ArrayCollection();
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

    public function getCourseKey(): string
    {
        return $this->courseKey;
    }

    public function setCourseKey(string $key): void 
    {
        $this->courseKey = str_replace([" ", "_", "-"], "_", strtolower($key));
    }


    public function getStudents(): Collection 
    {
        return $this->students;
    }

    public function addStudent(Student $student): void 
    {
        if(!$this->students->contains($student)) {
            $this->students->add($student);
            $student->addCourse($this);
        }
    }

    public function removeStudent(Student $student): void 
    {
        if($this->students->contains($student)) {
            $this->students->removeElement($student);
            $student->removeCourse($this);
        }
    }

    public function getGrades(): Collection
    {
        return $this->grades;
    }

    public function addGrade(Grade $grade): void
    {
        if(!$this->grades->contains($grade)) {
            $this->grades->add($grade);
            $grade->setCourse($this);
        }
    }

    public function removeGrade(Grade $grade):void 
    {
        if($this->grades->contains($grade)) {
            $this->grades->removeElement($grade);
            $grade->setCourse(null);
        }
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
