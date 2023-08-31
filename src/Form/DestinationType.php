<?php

namespace App\Form;

use App\Entity\Destination;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DestinationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('name')
            // ->add('description')
            // ->add('image')
            // ->add('articles')
            // ->add('categories')
   
            ->add('name', TextType::class, [
                'label' => 'Titre de la distination',
                'attr' => [
                    'class' => 'form-control mb-3 w-50'
                ],
            ])
            ->add(
                'description',TextType::class, [
                    'label' => 'description de la distination',
                    'attr' => [
                        'class' => 'form-control Textarea mb-3 w-75 ',
                    ],
                ])
    
            // ->add('articles', EntityType::class, [
            //     'class' => Article::class,
            //     'multiple' => true,
            //     'choice_label' => 'title',
            //     'label' => 'articles',
            //     'attr' => [
            //         'class' => 'form-control mt-3 mb-3 w-75'
            //     ],
            // ])
    
            ->add('image', FileType::class, [
                'mapped' => false, // On ne lie pas ce champ à la propriété 'image' de l'entité Article
                'label' => 'Téléversez l\'image de l\'article',
                'attr' => [
                    'class' => 'form-control mb-3 w-50'
                ]
            ])
            // ->add('destination', EntityType::class, [
            //     'class' => Destination::class,
            //     'multiple' => true,
            //     'choice_label' => 'name',
            //     'label' => 'destination',
            //     'attr' => [
            //         'class' => 'form-control mt-3 mb-3 w-75'
            //     ],
            // ])
            ;
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Destination::class,
        ]);
    }
}
