<?php

namespace App\Form;

use App\Entity\Locales;
use App\Entity\Amount;
use App\Entity\AmountTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AmountTranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          
            ->add('name', TextType::class,array(
                'label'=> 'name',
                'required' => false,
                'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'name']
            ))
            ->add('locales', EntityType::class, array(
                'class' => Locales::class,
                'choice_label' => 'name',
                'placeholder' => false,
                'label' => false,
                'attr' => ['class' => 'w3-input w3-select w3-border w3-hide']
            ))
        ;
    }
    /*
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => BannerTranslation::class,
        ));
    }*/
}