<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReloadAvatarFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      $builder
        ->add('size', ChoiceType::class, [
            'label' => 'Taille',
            'choices' => array_combine(range(3,7), range(3,7))
        ])
        ->add('nb-colors', ChoiceType::class, [
            'label' => 'Couleurs',
            'choices' => array_combine(range(2,4), range(2,4))
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
