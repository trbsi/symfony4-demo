<?php

namespace App\Form\College;

use App\Entity\College\Student;
use App\Entity\College\City;
use App\Entity\College\University;
use App\Entity\College\Course;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\College\GradeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\Valid;

class StudentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', null, [
                'attr' => ['autofocus' => true],
                'label' => 'label.name',
            ])
            ->add('city', EntityType::class, [
				    // looks for choices from this entity
				    'class' => City::class,
				    // uses the City.name property as the visible option string
				    'choice_label' => 'name',
				    'placeholder' => 'choose.city'
			]) 
			->add('university', EntityType::class, [
				    // looks for choices from this entity
				    'class' => University::class,
				    // uses the University.name property as the visible option string
				    'choice_label' => 'name',
			])
            ->add('grades', CollectionType::class, array(
                'entry_type' => GradeType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false, 
                'constraints' => array(new Valid()), //https://stackoverflow.com/a/37813228/1860890

            )) 
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
