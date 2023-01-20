<?php
namespace App\Form\Production\Publisher;

use App\Entity\Production\Publisher\Publisher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Translations\Types\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PublisherType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $entity = new Publisher;

        $developer = $options['developer'];

        $builder
            ->add("translations", ResourceTranslationsType::class, [
                'entry_type' => PublisherTranslationsType::class,
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
            'data_class' => Publisher::class,
            'developer' => null,
        ]);
    }
}
