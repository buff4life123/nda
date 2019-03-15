<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Locales;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('locales', EntityType::class, array(
                    'class' => Locales::class,
                    'choice_label' => 'name',
                    'placeholder' => 'Local',
                    'label' => 'Local',
                    'attr' => ['class' => 'w3-input w3-select w3-border w3-white']
            ))
            ->add('link_active', CheckboxType::class, array(
                'label'    => 'Ativo',
                'required' => false,
                'attr' => ['class' => 'w3-check']
            ))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Menu::class,
        ));
    }
}