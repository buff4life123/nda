<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\CategoryTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_active', CheckboxType::class, array(
                'label'    => 'active',
                'required' => false,
                'attr' => ['class' => 'w3-check']
            ))
            ->add('translation', CollectionType::class, array(
                'entry_type' => CategoryTranslationType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,                 
                'by_reference' => false,
                'label' => false   
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([            
            'data_class' => Category::class,
        ]);
    }
}
