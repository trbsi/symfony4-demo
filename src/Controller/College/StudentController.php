<?php
namespace App\Controller\College;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Form\College\StudentType;
use App\Entity\College\Student;
use App\Entity\College\Grade;
use App\Entity\College\Course;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\College\StudentRepository;

/**
 *
 * @Route("/student")
 *
 */
class StudentController extends AbstractController
{
    /**
     * @Route("/", name="list_students")
     * @Method("GET")
     */
    public function index(StudentRepository $studentRepository)
    {
        return $this->render('college/list_students.html.twig', 
        [
            'students' => $studentRepository->findAllStudents()
        ]);
    }

    /**
     * @Route("/add-student", name="add_student")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $student = new Student;
        $grade = new Grade;
        $student->getGrades()->add($grade);
        $grade2 = new Grade;
        $student->getGrades()->add($grade2);

        $form = $this->createForm(StudentType::class, $student)
        ->add('save', SubmitType::class)
        ;
        //dump($request); die;
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $grade->setStudent($student);
            $grade2->setStudent($student);
            $em->persist($student);
            $em->flush();

            $this->addFlash('success', 'student.created_successfully');
        }

        return $this->render('college/add_student.html.twig', 
        [
            'form' => $form->createView()
        ]);
    }
}
