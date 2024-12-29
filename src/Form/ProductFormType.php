<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom du produit',
                'attr' => [
                'class' => 'form',
            ]
            ])
            ->add('description', null, [
                'label' => 'Description',
                'attr' => [
                'class' => 'form',
            ]
            ])
            ->add('price', null, [
                'label' => 'Prix (€)',
                'attr' => [
                'class' => 'form',
            ]
            ])
            ->add('stock', null, [
                'label' => 'Quantité en stock',
                'attr' => [
                'class' => 'form',
            ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (formats autorisés : jpg, jpeg, png)',
                'attr' => [
                'class' => 'form',
                ],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez téléverser une image valide (jpg, jpeg, png)',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                'class' => 'form',
            ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}