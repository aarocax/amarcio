<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as choice;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class MemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('contribution', null, array(
            'label' => 'Quiero hacer una aportación de ',
            'attr' => array('value' => '10'),
          ))

        ->add('frequency', choice::class, array(
            'choices'  => array(
                'mensual' => 'mensual',
                'trimestral' => 'trimestral',
                'semestral' => 'semestral',
                'anual' => 'anual',
            ),
            'placeholder' => 'Seleccione la frecuencia...',
            'attr' => array(
                'class' => 'form-control',
                ),
            'label' => "Frecuencia *",
          ))

        ->add('name', null, array(
            'label' => 'Nombre *',
          ))
        ->add('surname', null, array(
            'label' => 'Apellidos *',
          ))
        ->add('nif', null, array(
            'label' => 'NIF/CIF *',
          ))
        ->add('iban', null, array(
            'label' => 'IBAN *',
          ))
        ->add('email', null, array(
            'label' => 'Email *',
          ))
        ->add('cp', null, array(
            'label' => 'Código postal *',
          ))
        ->add('phone', null, array(
            'label' => 'Teléfono *',
          ))
        
        ->add('known', choice::class, array(
            'choices'  => array(
                'Web' => 'web',
                'Eventos' => 'eventos',
                'Otros' => 'otros',
                'SMS' => 'sms',
                'Tríptico' => 'tríptico',
                'Facebook' => 'facebook',
                'Prensa' => 'prensa',
            ),
            'placeholder' => '¿Cómo nos has conocido?',
            'attr' => array(
                'class' => 'form-control',
                ),
            'label' => "¿Cómo nos has conocido?",
          ))

        ->add('correspondence', CheckboxType::class, array(
            'required' => false,
            'label' => '¿Quieres recibir correspondencia de la Fundación Andrés Marcio?',
          ))

        ->add('address', null, array(
            'label' => 'Dirección *',
          ))
        ->add('town', null, array(
            'label' => 'Población *',
          ))
        ->add('province', null, array(
            'label' => 'Provincia *',
          ))

        ->add('acceptConditions', CheckboxType::class, array(
            'required' => false,
            'label' => 'He leído y acepto la política de privacidad *',
          ))

        ;
        
    }
    
    /**
     * {@inheritdoc}
     */
    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults(array(
    //         'data_class' => 'AppBundle\Entity\Customer'
    //     ));
    // }

   

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
      return 'appbundle_hazte_socio';
    }


}
