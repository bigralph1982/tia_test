<?php

namespace App\Form\Backend;

use App\Entity\Backend\Administrators;
use App\Entity\Core\Users\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdministratorsType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $administrators = new Administrators();

        $dev = null;
        $profile = $options['profile'];
        
        if ($options['role'] == 999) {
            $dev = true;
        }

        $builder
            ->add('name', null, array('disabled' => $profile, 'label_attr' => array('class' => 'control-label'), 'label' => 'form.name', 'attr' => array('class' => 'form-control input-width-xxlarge' )))
            ->add('email', null, array('disabled' => $profile, 'label_attr' => array('class' => 'control-label'), 'label' => 'form.email', 'attr' => array('class' => 'form-control input-width-xxlarge')))

            ->add('text_password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array('label_attr' => array('class' => 'control-label'), 'attr' => array('class' => 'form-control', 'autocomplete' => 'new-password')),
                'required' => false,
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.repeatpassword'),
            ))
        ;

        if ($dev || !$profile) {
            $builder
                ->add('username', null, array('label_attr' => array('class' => 'control-label'), 'label' => 'form.username', 'attr' => array('class' => 'form-control')))
                ->add('role_id', ChoiceType::class, array('choices' => $administrators->getShowRolesArray($dev), 'label' => 'form.accesstype', 'attr' => array('class' => 'form-control')))
                ->add('isActive', ChoiceType::class, array('choices' => $administrators->getStatusArray(), 'label' => 'form.status', 'attr' => array('class' => 'form-control')))
                ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'role' => null,
            'user' => null,
            'profile' => false,
            'validation_groups' => null,
            'attr' => ['autocomplete' => 'off']
        ]);
    }
}
