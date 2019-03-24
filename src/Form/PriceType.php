<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Price;
use App\Entity\PriceTranslation;
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

class PriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, array(
                'class' => Product::class,
                'choice_label' => 'id',
                'placeholder' => 'product',
                'label' => 'product',
                'attr' => ['class' => 'w3-input w3-select w3-border w3-white']
            ))
            ->add('amount', MoneyPhpType::class, array(
                'label' => 'amount',
                'required'  => false,
                'attr' => ['placeholder'=>'amount', 'min'=>'0', 'step'=>'any', 'type'=>'number']
            ))
            ->add('translation', CollectionType::class, array(
                'entry_type' => PriceTranslationType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,                 
                'by_reference' => false,
                'label' => false   
            ))
            ->add('is_active', CheckboxType::class, array(
                'label'    => 'Ativa?',
                'required' => false,
                'attr' => ['class' => 'w3-check']
            ))
            ->add('isChild', CheckboxType::class, array(
                'label'    => 'Destaque',
                'required' => false,
                'attr' => ['class' => 'w3-check']
            ))
            ->add('submit', SubmitType::class,
            array(
                'label' => 'part_seven.submit',
                'attr' => ['class' => 'w3-btn w3-block w3-border w3-green w3-margin-top SAVE']
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