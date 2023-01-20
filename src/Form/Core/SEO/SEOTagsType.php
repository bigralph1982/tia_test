<?php

namespace App\Form\Core\SEO;

use App\Entity\Core\SEO\SEOTags;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\Core\SEO\SEOTagsTranslationsType;

use App\Translations\Types\ResourceTranslationsType;

class SEOTagsType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $developer = $options['developer'];

        $builder
            ->add("translations", ResourceTranslationsType::class, [
                'entry_type' => SEOTagsTranslationsType::class,
                'label' => false,
            ]);

        if ($developer) {
            $builder
                ->add('title', TextType::class, [
                    'label' => 'table.title', 'required' => true,
                    'attr' => ['class' => "form-control "]
                ])
                ->add('code', TextType::class, [
                    'label' => 'general.code', 'required' => true,
                    'attr' => ['class' => "form-control "]
                ]);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SEOTags::class,
            'developer' => null,
        ]);
    }
}
