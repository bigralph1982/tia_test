<?php
namespace App\Form\Production\Publisher;

use App\Entity\Production\Publisher\PublisherTranslations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class PublisherTranslationsType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
        ->add(
            'title',
            TextType::class,
            [
                'label' => 'table.title', 'required' => false,
                'attr' => ['class' => "form-control "]
            ]
        )
        ->add('shortDescription', null, [
            'label' => 'form.legend', 'required' => false,
            'attr' => ['class' => "form-control "]
        ])
        ->add('content', CKEditorType::class, [
            'label' => 'Description', 'required' => false,
            'attr' => ['class' => "form-control "]
        ]);
           

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PublisherTranslations::class,
        ]);
    }
}