<?php
namespace App\Form\Production\Publisher;

use App\Entity\Production\Author\Author;
use App\Entity\Production\Publisher\Publisher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Translations\Types\ResourceTranslationsType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('author', EntityType::class, [
                'class' => Author::class,
                "label" => "Author",
                'query_builder' => function(EntityRepository $get) {
                    return $get->createQueryBuilder('p')->where('p.status in (1,2)');
                }, 'attr' => ['class' => 'form-group '],'choice_label' => "translations[en].title", 'expanded' => true, 'multiple' => true, 'required' => false,
                
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
