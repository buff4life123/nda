<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\CategoryTranslation;
use App\Entity\Price;
use App\Entity\ProductDescriptionTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, array(
                'class' => Category::class,
                'choice_label' => 'id',
                'placeholder' => 'category',
                'label' => 'category'              
            ))
            ->add('image', FileType::class, array(
                'label' => false,
                'required' => false,
                'attr' => ['class' => 'w3-hide set-image','onchange' => 'loadFile(event)']
            ))
            ->add('availability', IntegerType::class,
            array(
                'required' => true,
                'label' => 'available',
                'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'available',]
            ))
            ->add('duration', TextType::class,
            array(
                'required' => true,
                'label' => 'duration',
                'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'duration', 'readonly' => true]
            ))
            ->add('price', CollectionType::class, array(
                'entry_type' => ProductDescriptionTranslationType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,                 
                'by_reference' => false,
                'label' => false   
            ))
            ->add('price', CollectionType::class, array(
                'entry_type' => PriceType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,                 
                'by_reference' => false,
                'label' => false   
            ))
            ->add('event', CollectionType::class, array(
                'entry_type' => EventType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,                 
                'by_reference' => false,
                'label' => false   
            ))
            ->add('is_active', CheckboxType::class, array(
                'label'    => 'active',
                'required' => false,
                'attr' => ['class' => 'w3-check']
            ))
            ->add('highlight', CheckboxType::class, array(
                'label'    => 'highlight',
                'required' => false,
                'attr' => ['class' => 'w3-check']
            ))
            ->add('warranty_payment', CheckboxType::class, array(
                'label'    => 'warranty_payment',
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
    /*
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Product::class,
        ));
    }*/
}