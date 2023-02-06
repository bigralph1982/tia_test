<?php
namespace App\Form\Production\Category;

use App\Entity\Production\Category\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
#
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

#

use App\Translations\Types\ResourceTranslationsType;

class CategoryType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $entity = new Category;
        $developer = $options['developer'];

        $builder
            ->add("translations", ResourceTranslationsType::class, [
                'entry_type' => CategoryTranslationsType::class,
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
            'data_class' => Category::class,
            'developer'=>null,
        ]);
    }
}