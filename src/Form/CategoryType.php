<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_active', CheckboxType::class, array(
                'label'    => 'active',
                'required' => false,
                'attr' => ['class' => 'w3-check']
            ))
            ->add('submit', SubmitType::class,
            array(
                'label' => 'submit',
                'attr' => ['class' => 'w3-btn w3-block w3-border w3-green w3-margin-top']
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([            
            'data_class' => Category::class,
        ]);
    }
}
