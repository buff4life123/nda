<?php

namespace App\Form;

use App\Entity\Amount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AmountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
         ->add('translation', CollectionType::class, array(
                'entry_type' => AmountTranslationType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,                 
                'by_reference' => false,
                'label' => false   
            ))

            ->add('amount', MoneyPhpType::class, array(
                'label' => 'amount',
                'required' => false,
                'attr' => ['placeholder'=>'amount', 'min'=>'0', 'step'=>'any', 'type'=>'number']
            ))
            ->add('is_active', CheckboxType::class, array(
                'label' => 'active',
                'required' => false,
                'attr' => ['class' => 'w3-check w3-margin-left']
            ))
            ->add('isChild', CheckboxType::class, array(
                'label' => 'dependent',
                'required' => false,
                'attr' => ['class' => 'w3-check w3-margin-left']
            ))
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' =>Amount::class,
        ));
    }
}