<?php
namespace App\Form\Production\Author;

use App\Entity\Production\Author\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
#
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

#

use App\Translations\Types\ResourceTranslationsType;

class AuthorType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $entity = new Author;

        $builder
            ->add("translations", ResourceTranslationsType::class, [
                'entry_type' => AuthorTranslationsType::class,
                'label' => false,
            ])

            ->add('ordering', null, [
                'label' => 'form.ordering', 'required' => false,
                'attr' => ['class' => "form-control "]
            ])
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
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}