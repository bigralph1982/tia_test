<?php
namespace App\Form\Production\Book;

use App\Entity\Production\Author\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Translations\Types\ResourceTranslationsType;
use App\Entity\Production\Book\Book;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class  BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = new Book();
        $developer = $options['developer'];
        $builder
                ->add("translations", ResourceTranslationsType::class, [
                    'entry_type' => BookTranslationsType::class,
                    'label' => false,
                ])
                ->add('author', EntityType::class, [
                    'class' => Author::class,
                    "label" => "Author",
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
            'data_class' => Book::class,
            'developer'=>null,
        ]);
    }
}