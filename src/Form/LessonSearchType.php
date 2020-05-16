<?php

namespace App\Form;

use App\Entity\Theme;
use App\Entity\Club;
use App\Entity\LessonSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LessonSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('themes', EntityType::class, [
                'required' => false,
                'label' => 'Thèmes',
                'class' => Theme::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('clubs', EntityType::class, [
                'required' => false,
                'label' => 'Clubs',
                'class' => Club::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LessonSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
