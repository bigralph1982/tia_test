<?php

namespace App\Form\Core\SEO;

use App\Entity\Core\SEO\SEOTagsTranslations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SEOTagsTranslationsType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            /*
                 * SEO Fields
                 */
            ->add('seo_title', TextType::class, [
                'label' => 'seo.title', 'required' => false,
                'attr' => ['class' => "form-control "]
            ])
            ->add('seo_description', null, [
                'label' => 'seo.description', 'required' => false,
                'attr' => ['class' => "form-control "]
            ])
            ->add('seo_keywords', null, [
                'label' => 'seo.keywords', 'required' => false,
                'attr' => ['class' => "form-control "]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SEOTagsTranslations::class,
        ]);
    }
}
