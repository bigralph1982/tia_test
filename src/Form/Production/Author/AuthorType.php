<?php
namespace App\Form\Production\Author;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Translations\Types\ResourceTranslationsType;
use App\Entity\Production\Author\Author;
use App\Entity\Production\Book\Book;
use App\Entity\Production\Publisher\Publisher;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class  AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = new Author();
        $developer = $options['developer'];
        $builder
                ->add("translations", ResourceTranslationsType::class, [
                    'entry_type' => AuthorTranslationsType::class,
                    'label' => false,
                ])
                ->add('book', EntityType::class, [
                    'label' => 'Book',
                    'class' => Book::class,
                    'query_builder' => function(EntityRepository $get) {
                        return $get->createQueryBuilder('p')->where('p.status in (1,2)');
                    }, 'attr' => ['class' => 'form-group '],'choice_label' => "translations[en].title", 'expanded' => true, 'multiple' => true, 'required' => false,
                    
                    
                ])

                ->add('publisher', EntityType::class, [
                    'label' => 'publisher',
                    'class' => Publisher::class,
                    'query_builder' => function(EntityRepository $get) {
                        return $get->createQueryBuilder('p')->where('p.status in (1,2)');
                    }, 'attr' => ['class' => 'form-group '],'choice_label' => "translations[en].title", 'expanded' => true, 'multiple' => true, 'required' => false,
                    
                    
                ])
                
                ->add('ordering', null, ['label' => 'form.ordering', 'required' => false,
                    'attr' => ['class' => "form-control input-width-xxlarge"]
                ])
                ->add('status', ChoiceType::class, ['choices' => $entity->getStatusArray(), 'label' => 'form.status',
                    'attr' => ['class' => 'form-control input-width-xxlarge']
                ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
            'developer'=>null,
        ]);
    }
}