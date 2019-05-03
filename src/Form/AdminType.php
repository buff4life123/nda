<?php

namespace App\Form;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('address', TextType::class, array(
                'label'=>'address',
                'required' => true,
                'attr' => ['class' => 'w3-input w3-padding-16','placeholder'=>'address']
            ))
            ->add('location', TextType::class, array(
                'label'=>'location',
                'required' => true,
                'attr' => ['class' => 'w3-input w3-padding-16','placeholder'=>'location']
            ))
            ->add('postalCode', TextType::class, array(
                'label'=>'postal_code',
                'required' => true,
                'attr' => ['class' => 'w3-input w3-padding-16','placeholder'=>'postal_code']
            ))
            ->add('nif', TextType::class, array(
                'label'=>'NIF',
                'required' => true,
                'attr' => ['class' => 'w3-input w3-padding-16','placeholder'=>'NIF']
            ))
            ->add('email', EmailType::class,
            array(
                'label'=>'email',
                'required' => true,
                'attr' => ['class' => 'w3-input w3-padding-16','placeholder'=>'email',]
            ))
            ->add('username', TextType::class, array(
                'label'=>'username',
                'required' => true,
                'attr' => ['class' => 'w3-input w3-padding-16','placeholder'=>'username','autofocus'=>'autofocus']
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password *', 'attr' => ['class' => 'w3-input w3-padding-16','placeholder'=>'Password *']),
                'second_options' => array('label' => 'Repetir Password *', 'attr' => ['class' => 'w3-input w3-padding-16','placeholder'=>'Repetir Password *'])
            ))
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            //'data_class' => Admin::class,
        ));
    }
}