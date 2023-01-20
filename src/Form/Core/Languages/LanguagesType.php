<?php

namespace App\Form\Core\Languages;

use App\Entity\Core\Languages\Languages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LanguagesType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity =  new Languages;
        $builder
            ->add('title', null, array('label' => 'table.title', 'required' => true, 'attr' => array('class' => "form-control")))
            ->add('code', null, array('label' => 'languages.code', 'required' => true, 'attr' => array('class' => "form-control")))
            ->add('cid', null, array('label' => 'languages.customid', 'required' => true, 'attr' => array('class' => "form-control")))
            ->add('isMain', null, array('label' => 'languages.ismainlang', 'required' => false, 'attr' => array('class' => "form-check-input")))
            ->add('status', ChoiceType::class, [
                'choices' => $entity->getStatusArray(), 'label' => 'form.status',
                'attr' => ['class' => 'form-control ']
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Languages::class,
        ));
    }
}
