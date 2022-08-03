<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CreateProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titleProject', TextType::class, array(
            'attr' => array(
                'class' => 'inputAdmin',
            )))
        ->add('contentProject', TextareaType::class, array(
            'attr' => array(
                'class' => 'textareaAdmin',
            )))
        ->add('imageProject', FileType::class, array(
            'data_class' => null,
            'required' => false,
            'attr' => array(
                'class' => 'hideUpload',
                'placeholder' => 'Upload'
            )))
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
