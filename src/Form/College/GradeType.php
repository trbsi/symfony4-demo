<?php

namespace App\Form\College;

use App\Entity\College\Student;
use App\Entity\College\City;
use App\Entity\College\University;
use App\Entity\College\Course;
use App\Entity\College\Grade;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\College\GradeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class GradeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('grade', NumberType::class, [
                'attr' => ['autofocus' => true],
                'label' => 'label.grade',
            ])
            ->add('course', EntityType::class, [
                    // looks for choices from this entity
                    'class' => Course::class,
                    // uses the University.name property as the visible option string
                    'choice_label' => 'name',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Grade::class,
        ]);
    }
}
