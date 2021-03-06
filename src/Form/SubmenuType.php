<?php

namespace App\Form;

use App\Entity\Submenu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;

class SubmenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active', CheckboxType::class, array(
                'label'    => 'active',
                'required' => false,
                'attr' => ['class' => 'w3-check']
            ))
            ->add('superuser', CheckboxType::class, array(
                'mapped' => false,
                'label'    => 'SuperUser',
                'required' => false,
                'attr' => ['class' => 'w3-check']
            ))
            ->add('admin', CheckboxType::class, array(
                'mapped' => false,
                'label'    => 'Admin',
                'required' => false,
                'attr' => ['class' => 'w3-check']
            ))
            ->add('manager', CheckboxType::class, array(
                'mapped' => false,
                'label'    => 'Manager',
                'required' => false,
                'attr' => ['class' => 'w3-check']
            ))
            ->add('icon', TextType::class, array(
                'label'    => 'Icon',
                'required' => false,
                'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'Icon']
            ))
            ->add('path', TextType::class, array(
                'label'    => 'Path',
                'required' => false,
                'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'Path']
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
        $resolver->setDefaults(array(
            'data_class' => Submenu::class,
        ));
    }
}