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
                    'Multiple Choice' => 'multiple',
                    'Single Choice' => 'single',
                    'Star Rating' => 'star',
                    'Textbox' => 'textbox',
                    'Contact Info' => 'contact',
                    'Dropdown' => 'dropdown',
                    'Slider' => 'slider',
                    'Date/Time' => 'datetime',
                ],
                'attr' => ['class' => 'form-control select-answer-type']
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


/***
 * 
 * 
 * 
 * 	<div class="form-group">
						<ul class="questions"   data-prototype="{{ form_widget(form.questions.vars.prototype)|e('html_attr') }}">
						{% for questionField in form.questions %}
								
								{{ form_errors(questionField) }}
								{{ form_row(questionField.title) }}
								{{ form_row(questionField.answer_type) }}
								 <ul class="form-group answers-area"  data-prototype="{{ form_widget(questionField.answers.vars.prototype)|e('html_attr') }}">
								{% for answer in questionField.answers %}
									{{ form_row(answer) }}
								{% endfor %}
								</ul> 
							
						{% endfor %}
						</ul>
					</div>

 */