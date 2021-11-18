<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nickname', TextType::class, [
                'label' => 'Pseudo',
                // 'constraints' => [
                //     new NotBlank(['message' => 'Le champ "pseudo" est obligatoire'])
                // ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Avis',
                // 'constraints' => [
                //     new NotBlank(['message' => 'Le champ "avis" est obligatoire']),
                //     new Length(['min' => 10, 'minMessage' => 'Votre avis doit comporter au moins 10 caractères'])
                // ]
            ])
            ->add('grade', ChoiceType::class, [
                'label' => 'Votre note',
                'choices' => array_combine(range(1,5), range(1,5))
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
