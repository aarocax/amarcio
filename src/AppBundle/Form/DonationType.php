<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as choice;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class DonationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('contribution', choice::class, array(
            'choices'  => array(
                '10€' => 10,
                '30€' => 30,
                '50€' => 50,
                '100€' => 100,
                'Otro' => 0,
            ),
            'data' => '10',
            'attr' => array(
                'class' => 'form-control',
                ),
            'label' => 'Quiero hacer un donativo único de ',
          ))

          ->add('otro', null, array(
            'label' => 'Importe del donativo *',
            'required' => false,
            'mapped' => false,
          ))

          ->add('paymentMedia', choice::class, array(
            'choices'  => array(
                'Tarjeta de crédito' => 'card',
                'PayPal' => 'paypal',
            ),
            'attr' => array(
                'class' => 'form-control',
                ),
            'label' => 'Elige la forma de pago *',
            'expanded' => true,
          ))

          ->add('name', null, array(
            'label' => 'Nombre *',
          ))
	        ->add('surname', null, array(
	            'label' => 'Apellidos *',
	          ))
	        ->add('email', null, array(
	            'label' => 'Email *',
	          ))
	        ->add('nif', null, array(
	            'label' => 'NIF/CIF *',
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
      return 'appbundle_haz_un_donativo';
    }


}
