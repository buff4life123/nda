<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,
            array(
                'label'=>'Email',
                'required' => true,
                'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'Email']
            ))
            ->add('username', TextType::class, array(
                'label'=>'name',
                'required' => true,
                'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'name','autofocus'=>'autofocus']
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password *', 'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'Password *']),
                'second_options' => array('label' => 'repeat_pass', 'attr' => ['class' => 'w3-input w3-border w3-white','placeholder' => 'repeat_pass'])
            ))
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}