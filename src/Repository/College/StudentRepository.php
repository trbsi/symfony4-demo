<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository\College;

use App\Entity\College\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;

class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function findAllStudents()
    {
        return $this->createQueryBuilder('s')
        ->select('s, u, c, courses, grades')
        ->join('s.university', 'u')
        ->join('s.city', 'c')
        ->leftJoin('s.courses', 'courses')
        ->leftJoin('s.grades', 'grades')
        ->getQuery()
        ->getResult();

    }

    public function getOneStudent($id)
    {
        return $this->createQueryBuilder('s')
        ->select('s, c, g, u, courses')
        ->join('s.city', 'c')
        ->leftJoin('s.grades', 'g')
        ->join('s.university', 'u')
        ->leftJoin('s.courses', 'courses')
        ->where('s.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getSingleResult();
    }
}
