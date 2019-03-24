<?php

namespace App\Form;

use App\Entity\Locales;
use App\Entity\Price;
use App\Entity\PriceTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PriceTranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('locales', EntityType::class, array(
                'class' => Locales::class,
                'choice_label' => 'name',
                'placeholder' => 'name',
                'label' => 'name',
                'attr' => ['class' => 'w3-input w3-select w3-border w3-white']
            ))
            ->add('product', EntityType::class, array(
                'class' => Price::class,
                'choice_label' => 'id',
                'placeholder' => 'amount',
                'label' => 'amount',
                'attr' => ['class' => 'w3-input w3-select w3-border w3-white']
            ))
            ->add('name', TextType::class,array(
                'label'=> 'name',
                'required' => false,
                'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'name']
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