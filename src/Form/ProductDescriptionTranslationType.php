<?php

namespace App\Form;

use App\Entity\ProductDescriptionTranslation;
use App\Entity\Locales;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProductDescriptionTranslationType extends AbstractType
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
                'placeholder' => 'Local',
                'label' => 'Local',
                'attr' => ['class' => 'w3-input w3-select w3-border w3-white']
            ))
            ->add('product', EntityType::class, array(
                'class' => Product::class,
                'choice_label' => 'id',
                'placeholder' => 'product',
                'label' => 'product',
                'attr' => ['class' => 'w3-input w3-select w3-border w3-white']
            ))
            ->add('name', TextType::class,array(
                'label'=> 'name',
                'required' => false,
                'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'name']
            ))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ProductDescriptionTranslation::class,
        ));
    }
}