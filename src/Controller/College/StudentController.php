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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 *
 * @Route("/student")
 *
 */
class StudentController extends AbstractController
{
    /**
     * @Route("", name="list_students")
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
     * @Security("has_role('ROLE_ADMIN')")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $student = new Student;
        $grade = new Grade;
        $student->addGrade($grade);

        $form = $this->createForm(StudentType::class, $student)
        ->add('save', SubmitType::class)
        ;
        //dump($request); die;
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $grade->setStudent($student);
            $em->persist($student);
            $em->flush();

            $this->addFlash('success', 'student.created_successfully');
        }

        return $this->render('college/add_student.html.twig', 
        [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit-student/{id}/edit", requirements={"id": "\d+"}, name="edit_student")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, StudentRepository $studentRepository)
    {
        $student = $studentRepository->getOneStudent($request->get('id'));

        $form = $this->createForm(StudentType::class, $student)
        ->add('edit', SubmitType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();

            $this->addFlash('success', 'student.updated');

            return $this->redirectToRoute('edit_student', ['id' => $student->getId()]);
        }

        return $this->render('college/edit_student.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/delete/{id}", requirements={"id": "\d+"}, name="delete_student")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     * @return [type] [description]
     */
    public function delete($id, Request $request, StudentRepository $studentRepository)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('list_students');
        }

        /*$result = $this->getDoctrine()
        ->getRepository(Student::class)
        ->find($id);*/

        $entityManager = $this->getDoctrine()->getManager();
        $result = $studentRepository->find($id);
        $entityManager->remove($result);
        $entityManager->flush();

        $this->addFlash('success', 'Student deleted');

        return $this->redirectToRoute('list_students');

    }
}
