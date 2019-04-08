<?php

namespace App\Form;

use App\Entity\ProductWPTranslation;
use App\Entity\Locales;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ProductWPTranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('html', HiddenType::class,array(
                'required' => false
            ))
            ->add('locales', EntityType::class, array(
                'class' => Locales::class,
                'choice_label' => 'name',
                'label' => false,
                'attr' => ['class' => 'w3-input w3-select w3-border w3-white w3-hide']
            ))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ProductWPTranslation::class,
        ));
    }
}