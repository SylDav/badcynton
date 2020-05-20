<?php

namespace App\Form;

use App\Entity\Payment;
use App\Entity\Season;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('season', EntityType::class, [
                'required' => false,
                'class' => Season::class,
            ])
            ->add('user', EntityType::class, [
                'required' => false,
                'class' => User::class,
                'choice_label' => 'formName'
            ])
            ->add('amount')
            ->add('amount_paid')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
            'translation_domain' => 'forms'
        ]);
    }
}
