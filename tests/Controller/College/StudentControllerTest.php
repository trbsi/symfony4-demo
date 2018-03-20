<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Controller\College;

use App\Entity\College\Student;
use App\Entity\College\City;
use App\Entity\College\University;
use App\Entity\College\Course;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Functional test for the controllers defined inside BlogController.
 *
 * See https://symfony.com/doc/current/book/testing.html#functional-tests
 *
 * Execute the application tests using this command (requires PHPUnit to be installed):
 *
 *     $ cd your-symfony-project/
 *     $ ./vendor/bin/phpunit
 */
class StudentControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/en/student');

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('.table tbody tr')->count(),
            'It has more than 1 item on the page'
        );

        //edit is not displayed
        $this->assertCount(0, $crawler->filter('.edit'));
    }

    /**
     * @dataProvider urlsDenyRegularUserAccess
     * @return [type] [description]
     */
    public function testDenyRegularUserAccess($httpMethod, $url)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'john_user',
            'PHP_AUTH_PW' => 'kitten',
        ]);

        $client->request($httpMethod, $url);
        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider urlsDenyRegularUserAccess
     * @return [type] [description]
     */
    public function testAllowAdminAccess($httpMethod, $url)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'jane_admin',
            'PHP_AUTH_PW' => 'kitten',
        ]);

        $client->request($httpMethod, $url, [
            'token' => 'test'
        ]);
        if($url == '/en/student/delete/1') {
            $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        } else {
            $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        }
    }

    public function urlsDenyRegularUserAccess()
    {
        yield ['GET', '/en/student/add-student'];
        yield ['POST', '/en/student/add-student'];
        yield ['GET', '/en/student/edit-student/1/edit'];
        yield ['POST', '/en/student/edit-student/1/edit'];
        yield ['POST', '/en/student/delete/1'];
    }

    public function testAdminAddStudent()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'jane_admin',
            'PHP_AUTH_PW' => 'kitten',
        ]);

        $city = $client->getContainer()->get('doctrine')->getRepository(City::class)->find(1);
        $university = $client->getContainer()->get('doctrine')->getRepository(University::class)->find(4);
        $course = $client->getContainer()->get('doctrine')->getRepository(Course::class)->find(1);

        $crawler = $client->request('GET', '/en/student/add-student');
        $btnCrawler = $crawler->selectButton('Save');
        $form = $btnCrawler->form([
            'student[name]' => 'Ime studenta',
        ]);

        $form['student[city]']->select(1);
        $form['student[university]']->select(4);
        $form['student[courses]']->setValue([1,2]); //https://github.com/symfony/symfony/issues/5562#issuecomment-8748443
        $form['student[grades][0][grade]'] = 5; 
        $form['student[grades][0][course]']->select(1);

        $client->submit($form);

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $student = $client->getContainer()->get('doctrine')->getRepository(Student::class)->findOneBy([
            'name' => 'Ime studenta',
        ]);
        $this->assertNotNull($student);
        //test basic
        $this->assertSame('Ime studenta', $student->getName());
        $this->assertSame($city->getCityKey(), $student->getCity()->getCityKey());
        $this->assertSame($university->getUniKey(), $student->getUniversity()->getUniKey());
        //test courses
        foreach ($student->getCourses() as $course) {
            $courses[] = $course->getId();
        }
        foreach ([1,2] as $course_id) {
            $this->assertContains($course_id, $courses);
        }

        //test grades
        foreach ($student->getGrades() as $grade) {
            $this->assertSame($grade->getGrade(), 5);
            $this->assertSame($grade->getCourse()->getId(), 1);
        }
    }

    public function testAdminDeleteStudent()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'jane_admin',
            'PHP_AUTH_PW' => 'kitten',
        ]);
        $crawler = $client->request('GET', '/en/student');
        $form = $crawler->filter('.delete-form')->first()->form();
        $client->submit();

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $student = $client->getContainer()->get('doctrine')->getRepository(Student::class)->find(1);
        $this->assertNull($student);
    }

    public function testAdminEditStudent()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'jane_admin',
            'PHP_AUTH_PW' => 'kitten',
        ]);
        $crawler = $client->request('GET', '/en/student/edit-student/1/edit');
        $form = $crawler->selectButton('Edit')->form([
            'student[name]' => 'Novi student',
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $student = $client->getContainer()->get('doctrine')->getRepository(Student::class)->find(1);
        $this->assertSame('Novi student', $student->getName());
    }
}
