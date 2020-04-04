<?php

namespace App\Form;

use App\Entity\Lesson;
use App\Entity\Theme;
use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LessonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'choice',
            ])
            ->add('theme', EntityType::class, [
                'class' => Theme::class,
                'choice_label' => 'name'
            ])
            ->add('club', EntityType::class, [
                'class' => Club::class,
                'choice_label' => 'name'
            ])
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lesson::class,
            'translation_domain' => 'forms'
        ]);
    }
}
