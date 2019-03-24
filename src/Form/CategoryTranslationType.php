<?php

namespace App\Form;

use App\Entity\CategoryTranslation;
use App\Entity\Category;
use App\Entity\Locales;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CategoryTranslationType extends AbstractType
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
            ->add('category', EntityType::class, array(
                'class' => Category::class,
                'choice_label' => 'id',
                'placeholder' => 'category',
                'label' => 'category',
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
            'data_class' => CategoryTranslation::class,
        ));
    }
}