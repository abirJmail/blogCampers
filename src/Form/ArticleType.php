<?php

namespace App\Form;


use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void


    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'article',
                'attr' => [
                    'class' => 'form-control mb-3 w-50'
                ],
            ])
            ->add(
                'content',TextType::class, [
                    'label' => 'content de l\'article',
                    'attr' => [
                        'class' => 'form-control Textarea mb-3 w-75 ',
                    ],
                ])

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'multiple' => true,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'attr' => [
                    'class' => 'form-control mt-3 mb-3 w-75'
                ],
            ])

            ->add('image', FileType::class, [
                'mapped' => false, // On ne lie pas ce champ à la propriété 'image' de l'entité Article
                'label' => 'Téléversez l\'image de l\'article',
                'attr' => [
                    'class' => 'form-control mb-3 w-50'
                ]
            ])
            ->add('isPublished', CheckboxType::class, [
                'required' => false,
                'label' => 'Voulez-vous publier l\'article ?',
                'attr' => [
                    'class' => 'form-check-input mx-3 mb-3 ',
                    'type' => 'checkbox',
                ]
            ])
            // ->add('Publier', SubmitType::class, [
            //     'attr' => [
            //         'class' => 'btn btn-primary rounded-pill mb-3'
            //     ]
            // ]);
            ;
    }




    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
