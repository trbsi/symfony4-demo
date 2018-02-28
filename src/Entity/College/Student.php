<?php

namespace App\Entity\College;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="students")
 */
class Student
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
     * @Assert\NotBlank(message="student.name")
     * @Assert\Length(
     *     min=5,
     *     minMessage="student.too_short",
     *     max=10000,
     *     maxMessage="student.too_long"
     * )
     */
    private $name;

    /**
     * @var City
     *
     * @Assert\NotBlank(message="student.city")
     * @ORM\ManyToOne(targetEntity="City", inversedBy="students")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id", nullable=false)
     */
    private $city;

    /**
     * @var University
     *
     * @ORM\OneToOne(targetEntity="University", inversedBy="student")
     * @ORM\JoinColumn(name="university_id", referencedColumnName="id", nullable=false)
     */
    private $university;

    /**
     * @var Course
     * Many Students have Many Courses.
     * @ORM\ManyToMany(targetEntity="Course", inversedBy="students")
     * @ORM\JoinTable(name="courses_students_pivot",
     * joinColumns={
     *     @ORM\JoinColumn(name="student_id", referencedColumnName="id", onDelete="CASCADE")
     * },
     * inverseJoinColumns={
     *     @ORM\JoinColumn(name="course_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     * 
     */
    private $courses;

    /**
     * @var Grade[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Grade", mappedBy="student", cascade={"persist", "remove"}, orphanRemoval=TRUE)
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
        $this->courses = new ArrayCollection();
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

    public function getCity(): City
    {
        return $this->city;
    }

    public function setCity(City $city): void 
    {
        $this->city = $city;
    }

    public function getUniversity(): University
    {
        return $this->university;
    }

    public function setUniversity(University $uni): void
    {
        $this->university = $uni;
    }

    public function addCourse(Course ...$courses): void
    {
        foreach ($courses as $key => $course) {
            if(!$this->courses->contains($course)) {
                $this->courses->add($course);
            }
        }
    }

    public function removeCourse(Course $course): void 
    {
        $this->courses->removeElement($course);
    }

    public function getCourses(): Collection 
    {
        return $this->courses;
    }

    public function getGrades(): Collection
    {
        return $this->grades;
    }

    public function addGrade(Grade $grade): void
    {
        if(!$this->grades->contains($grade)) {
            $this->grades->add($grade);
            $grade->setStudent($this);
        }
    }

    public function removeGrade(Grade $grade): void 
    {
        if($this->grades->contains($grade)) {
            $this->grades->removeElement($grade);
            $grade->setStudent(null);
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
