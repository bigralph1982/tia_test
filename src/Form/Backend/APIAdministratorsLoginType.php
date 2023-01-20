<?php

namespace App\Form\Backend;

use App\Entity\Backend\Administrators;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;

class APIAdministratorsLoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('api_username', null, [])
            ->add('text_password', null, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ]);
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Administrators::class,
            'csrf_protection' => false,
        ]);
    }
}
