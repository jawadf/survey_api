<?php
 
namespace App\Form\Type; 

use App\Entity\Question;
use App\Entity\User;
use App\Entity\Survey;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'attr' => ['class' => 'form-control']
            ])
            // ->add('survey', EntityType::class, [
            //     'class' => Survey::class,
            //     'choice_label' => 'id',
            //     'attr' => ['class' => 'form-control']
            // ])
            ->add('answer_type', ChoiceType::class, [
                'choices'  => [
                    'Multiple Choice' => 'one-question-per-screen',
                    'Single Choice' => 'all-questions-one-screen',
                    'Star Rating' => 'all-questions-one-screen',
                    'Textbox' => 'all-questions-one-screen',
                    'Contact Info' => 'all-questions-one-screen',
                    'Dropdown' => 'all-questions-one-screen',
                    'Slider' => 'all-questions-one-screen',
                    'Date/Time' => 'all-questions-one-screen',
                ],
                'attr' => ['class' => 'form-control']
            ])
            
            ->add('answers', CollectionType::class, [
                'entry_type' => TextType::class,
                'attr' => ['class' => 'answers-list'],
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
            'data_class' => Question::class,
        ]);
    }
}