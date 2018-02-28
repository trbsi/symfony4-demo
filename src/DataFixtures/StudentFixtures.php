<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\User;
use App\DataFixtures\AppFixtures;
use App\Entity\College\Student;
use App\Entity\College\City;
use App\Entity\College\University;
use App\Entity\College\Course;

class StudentFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct()
    {
    }

    public function getDependencies()
    {
        return array(
            AppFixtures::class,
        );
    }

    public function load(ObjectManager $manager)
    {
        $this->loadCities($manager);
        $this->loadUniversities($manager);
        $this->loadCourses($manager);
        $this->loadStudents($manager);
    }

    private function loadCourses($manager)
    {
        $this->courses = ['Mehanika', 'Ekonomija', 'Biologija', 'Kemija'];
        foreach ($this->courses as $value) {
            $model = new Course;
            $model->setName($value);
            $model->setCourseKey($value);
            $manager->persist($model);
        }
        $manager->flush();
    }

    private function loadCities($manager)
    {
        $this->cities = ['Osijek', 'Zagreb', 'Split', 'Rijeka'];
        foreach ($this->cities as $value) {
            $city = new City;
            $city->setName($value);
            $city->setCityKey($value);
            $manager->persist($city);
        }
        $manager->flush();
    }

    private function loadUniversities($manager)
    {
        $this->universities = ['ETF', 'FER', 'MEFOS'];
        foreach ($this->universities as $value) {
            $uni = new University;
            $uni->setName($value);
            $uni->setUniKey($value);
            $manager->persist($uni);
        }
        $manager->flush();
    }

    private function loadStudents($manager)
    {
        $users = $manager->getRepository(User::class)->findAll();
        $city = $manager->getRepository(City::class);
        $uni = $manager->getRepository(University::class);
        $course = $manager->getRepository(Course::class);

        $courses = [
            'jane_admin' => ['biologija', 'kemija'],
            'tom_admin' => ['ekonomija', 'mehanika'],
            'john_user' => ['ekonomija'],
        ];

        foreach ($users as $key => $user) {
            $student = new Student;
            $student->setName($user->getFullname());
            $student->setCity($city->findOneBy(['cityKey' => strtolower($this->cities[array_rand($this->cities)]) ]));
            $student->setUniversity($uni->findOneBy(['uniKey' => strtolower($this->universities[$key]) ]));
            
            foreach ($courses[$user->getUsername()] as $key => $value) {
                $student->addCourse($course->findOneBy(['courseKey' => $value]));
            }

            $manager->persist($student);
        }

        $manager->flush();
    }
}
