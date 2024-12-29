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
            'attr' => [
                'class' => 'form',
                'placeholder' => 'Enter product name',
            ],
            'label_attr' => [
                'class' => 'form-label',
            ],
        ])
        ->add('description', null, [
            'attr' => [
                'class' => 'form',
                'placeholder' => 'Enter product description',
            ],
        ])
        ->add('price', null, [
            'attr' => [
                'class' => 'form',
                'placeholder' => 'Enter product price',
            ],
        ])
        ->add('stock', null, [
            'attr' => [
                'class' => 'form',
                'placeholder' => 'Enter product stock',
            ],
        ])
        ->add('image', FileType::class, [
            'label' => 'Image (jpg, jpeg, png)',
            'mapped' => false,
            'required' => false,
            'attr' => [
                'class' => 'form',
            ],
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/jpg',
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid image (jpg, jpeg, png)',
                ]),
            ],
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Submit',
            'attr' => [
                'class' => 'form',
            ],
        ]);
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
