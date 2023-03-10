<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Translations\Types;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Service\Core\Languages\LanguagesService;

/**
 * @author Anna Walasek <anna.walasek@lakion.com>
 */
final class ResourceTranslationsType extends AbstractType implements EventSubscriberInterface
{

    /**
     * @var string[]
     */
    private $definedLocalesCodes;

    /**
     * @var string
     */
    private $defaultLocaleCode;


    public function __construct(LanguagesService $languagesService)
    {

        $this->definedLocalesCodes = $languagesService->getLanguages();
        $this->defaultLocaleCode = $languagesService->getMain();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber($this);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::SUBMIT => 'submit',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $type = $form->getConfig()->getOption('entry_type');



        foreach ($this->definedLocalesCodes as $localeTitle => $localeCode) {

            if ($form->has($localeCode)) {
                continue;
            }

            $required = $localeCode === $this->defaultLocaleCode;
            $form->add($localeCode, $type, [
                'required' => $required,
                //   'label' => $localeTitle,
            ]);
        }
    }

    /**
     * @param FormEvent $event
     */
    public function submit(FormEvent $event)
    {
        /** @var TranslationInterface[] $translations */
        $translations = $event->getData();
        $translatable = $event->getForm()->getParent()->getData();

        foreach ($translations as $localeCode => $translation) {


            if (null === $translation) {
                unset($translations[$localeCode]);

                continue;
            }

            $translation->setLocale($localeCode);
            $translation->setTranslatable($translatable);
        }

        $event->setData($translations);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return CollectionType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'store_translations';
    }
}
