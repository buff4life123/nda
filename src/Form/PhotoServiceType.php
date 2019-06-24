<?php

namespace App\Form;

use App\Entity\PhotoService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType; 
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\ORM\EntityRepository;

class PhotoServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
            array(
                'required' => true,
                'label' => 'name',
                'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'name']
            ))

            ->add('email', EmailType::class,
            array(
                'required' => true,
                'label' => 'Email',
                'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'Email']
            ))

            ->add('telephone', TelType::class,
            array(
                'required' => true,
                'label' => 'telephone',
                'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'telephone']
            ))

            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'required' => true,
                'multiple' => true,
                'attr' => ['class' => 'w3-hide set-image', 'id' => 'previews', 'accept' => 'image/*' ]
            ])
            // ->add('created_date', HiddenType::class,
            // array(
            //     'required' => false,
            //     'label' => 'date',
            //     'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'date']
            // ))

            // ->add('folder', HiddenType::class,
            // array(
            //     'required' => false,
            //     'label' => 'folder',
            //     'attr' => ['class' => 'w3-input w3-border w3-white','placeholder'=>'folder']
            // ))
            
            ->add('submit', SubmitType::class,
            array(
                'label' => 'save',
                'attr' => ['class' => 'w3-btn w3-block w3-border w3-green w3-margin-top']
            ))
        ;
    }
    /*
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Gallery::class,
        ));
    }*/
}