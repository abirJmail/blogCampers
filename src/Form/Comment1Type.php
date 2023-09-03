<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Comment1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('content')
            // ->add(
            //     'content',
            //     CKEditorType::class,
            //     array(
            //         'config' => array(
            //             'uiColor' => '#ffffff',
            //             'toolbar' => 'basic',
            //         )
            //     )
            // )
            ->add(
                'content',CKEditorType::class, [
                    'label' => 'Laisser un commentaire',
                    'attr' => [
                        'class' => 'form-control Textarea mb-3 w-75 ',
                    ],
                ])
            // ->add('createdAt')
            // ->add('article')
            // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
