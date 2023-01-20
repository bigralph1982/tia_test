<?php
namespace App\Form\Production\Publisher;

use App\Entity\Production\Publisher\PublisherTranslations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class PublisherTranslationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
        $builder
                ->add('title', TextType::class, ['label' => 'table.title', 'required' => false,
                    'attr' => ['class' => "form-control input-width-xxlarge"]
                        ]
                )
                
                ->add('shortDescription', null, ['label' => 'form.legend', 'required' => false,
                    'attr' => ['class' => "form-control input-width-xxlarge"]
                ])
                ->add('content', CKEditorType::class, ['label' => 'form.content', 'required' => false,
                    'attr' => ['class' => "form-control input-width-xxlarge"]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PublisherTranslations::class
        ]);
    }
}