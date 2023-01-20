<?php

namespace App\Form\Core\Settings;

use App\Entity\Core\Settings\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
        $builder->add('senderName', null, [
            'label' => 'settings.senderName', 'required' => false,
            'attr' => ['class' => "form-control input-width-xxlarge"],
        ])
        ->add('replyToEmail', null, [
            'label' => 'settings.replyToEmail', 'required' => false,
            'attr' => ['class' => "form-control input-width-xxlarge"],
        ])
        ->add('mailerHost', null, ['label' => 'settings.mailerHost', 'required' => false,
        'attr' => ['class' => "form-control input-width-xxlarge"],
        ])
        ->add('mailerPort', null, ['label' => 'settings.mailerPort', 'required' => false,
        'attr' => ['class' => "form-control input-width-xxlarge"],
        ])
        ->add('mailerEncryption', null, ['label' => 'settings.mailerEncryption', 'required' => false,
        'attr' => ['class' => "form-control input-width-xxlarge"],
        ])
        ->add('mailerTransport', null, ['label' => 'settings.mailerTransport', 'required' => false,
        'attr' => ['class' => "form-control input-width-xxlarge"],
        ])
        ->add('mailerUsername', null, ['label' => 'settings.mailerUsername', 'required' => false,
        'attr' => ['class' => "form-control input-width-xxlarge"],
        ])
        ->add('mailerEmail', null, ['label' => 'settings.mailerEmail', 'required' => false,
        'attr' => ['class' => "form-control input-width-xxlarge"],
        ])
        ->add('mailerPassword', null, ['label' => 'settings.mailerPassword', 'required' => false,
        'attr' => ['class' => "form-control input-width-xxlarge"],
        ])
        ->add('recordsPerPage', null, [
        'label' => 'settings.recordsPerPage', 'required' => false,
        'attr' => ['class' => "form-control input-width-xxlarge"],
        ])
        ->add('enableTwoStepAuthentication', CheckboxType::class, [
        'label' => 'settings.enableTwoStepAuthentication', 'required' => false,
        'attr' => ['class' => "form-check-input"],
        ])
        ->add('file', FileType::class, [
            "label" => "settings.placeholderImage",
            "required" => false,
            "attr" => [
                "accept" => "image/*",
                "class" => "form-control imgfile upload-image",
            ]
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Settings::class,
            'developer' => null,
        ]);
    }
}
