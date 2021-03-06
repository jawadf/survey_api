<?php
 
namespace App\Form\Type; 

use App\Entity\Survey;
use App\Entity\Business;
use App\Entity\Branch;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\QuestionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class SurveyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextType::class,[
                'attr' => ['class' => 'form-control']
            ]) 
            ->add('format', ChoiceType::class, [
                'choices'  => [
                    'One question per screen' => 'one-question-per-screen',
                    'All questions on one screen' => 'all-questions-one-screen',
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('business', EntityType::class, [
                'class' => Business::class,
                'required' => false,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control hidden-row']
            ])
            ->add('branches', EntityType::class, [
                'class' => Branch::class,
                 'multiple' => true,
                'choice_label' => 'name',
                'label' => 'add to branches',
                'attr' => ['class' => 'form-control kt-selectpicker']
            ])
            ->add('questions', CollectionType::class, [
                'entry_type' => QuestionType::class,
                'attr' => ['class' => 'form-control'],
                'label' => 'Add answer options',
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Survey::class,
        ]);
    }
}