<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $thumbnailFileOptions = [
            'label' => 'Image',
            'mapped' => false,
            'constraints' => [
                new Image([
                    'mimeTypesMessage' => 'Merci de charger un fichier image (jpg, gif, png)',
                    'maxSize' => '500k',
                    'maxSizeMessage' => 'Fichier trop volumineux (500 Ko maximum)'
                ])
            ]
        ];

        // On récupère le produit associé au formulaire
        $product = $builder->getData();

        // Si on est en AJOUT (nouveau produit)
        if (!$product->getId()) {

            // Le champ image doit être obligatoire
            $thumbnailFileOptions['constraints'][] = new NotBlank([
                'message' => 'Ce champ est obligatoire'
            ]);
        }

        // Si on est en MODIFICATION (produit existant)
        else {

            // Le champ image doit être facultatif (si on souhaite conserver l'image existante)
            $thumbnailFileOptions['required'] = false;
        }

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit'
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie'
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix en euros',
                'divisor' => 100,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du produit'
            ])
            ->add('thumbnailFile', FileType::class, $thumbnailFileOptions)            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
