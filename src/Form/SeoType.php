<?php

namespace App\Form;

use App\Entity\Seo;
use App\Entity\Locales;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,
            array(
                'required' => false,
                'label' => 'title',
                'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'title tag']
            ))

            ->add('description', TextareaType::class,
            array(
                'required' => false,
                'label' => 'description',
                'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'description', 'rows' => 3]
            ))

            ->add('keywords', TextareaType::class,
            array(
                'required' => false,
                'label' => 'keywords',
                'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'keywords', 'rows' => 3]
            ))

            ->add('locales', EntityType::class, array(
                'class' => Locales::class,
                'choice_label' => 'name',
                'placeholder' => false,
                'label' => false,
                'attr' => ['class' => 'w3-input w3-select w3-border w3-white']
            ))
            // ->add('product_description_translation', CollectionType::class, array(
            //     'entry_type' => ProductDescriptionTranslationType::class,
            //     'entry_options' => array('label' => false),
            //     'allow_add' => true,
            //     'allow_delete' => true,                 
            //     'by_reference' => false,
            //     'label' => false   
            // ))
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Seo::class,
        ));
    }
}