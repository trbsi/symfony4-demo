<?php

namespace App\Entity\College;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="grades",
 * uniqueConstraints={@ORM\UniqueConstraint(name="student_course_unique",columns={"student_id", "course_id"})})
 */
class Grade
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
     * @var Student
     *
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="grades")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id", nullable=FALSE, onDelete="CASCADE")
     */
    private $student;

    /**
     * @var Course
     *
     * @Assert\NotBlank(message="should_not_be_blank")
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="grades")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id", nullable=FALSE, onDelete="CASCADE")
     */
    private $course;

    /**
     * @var int
     *
     *  @Assert\Range(
     *      min = 1,
     *      max = 5,
     *      minMessage = "Min should be {{ limit }}",
     *      maxMessage = "Max should be {{ limit }} " )
     * @Assert\Type(type="integer")
     * @Assert\NotBlank(message="should_not_be_blank")
     * @ORM\Column(type="smallint")
     */
    private $grade;

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
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getStudent(): Student 
    {
        return $this->student;
    }

    public function setStudent(Student $student = null): void
    {
        $this->student = $student;
    }

    public function getCourse(): ?Course 
    {
        return $this->course;
    }

    public function setCourse(Course $course = null): void
    {
        $this->course = $course;
    }

    public function getGrade(): ?int  
    {
        return $this->grade;
    }

    public function setGrade(int $grade = null): void
    {
        $this->grade = $grade;
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
