<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ArchetypeBundle\Form\Type;

use Sylius\Bundle\ArchetypeBundle\Form\EventListener\ParentArchetypeListener;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductOptionChoiceType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAttributeValueType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Product archetype form type.
 *
 * @author Adam Elsodaney <adam.elso@gmail.com>
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 * @author Cyril Chapellier <tchap@tchap.me>
 */
class ArchetypeType extends AbstractResourceType
{
    /**
     * @var string
     */
    private $subject;

    /**
     * @param string $dataClass
     * @param array  $validationGroups
     * @param string $subject
     */
    public function __construct($dataClass, array $validationGroups, $subject)
    {
        parent::__construct($dataClass, $validationGroups);

        $this->subject = $subject;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventSubscriber(new ParentArchetypeListener($this->subject))
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('translations', ResourceTranslationsType::class, [
                'type' => ArchetypeTranslationType::class,
                'label' => 'sylius.form.archetype.name',
            ])
            ->add('attributes', CollectionType::class, [
                'entry_type' => ProductAttributeValueType::class,
                'required' => false,
                'multiple' => true,
                'label' => 'sylius.form.archetype.attributes',
            ])
            ->add('options', ProductOptionChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'label' => 'sylius.form.archetype.options',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return sprintf('sylius_%s_archetype', $this->subject);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return sprintf('sylius_%s_archetype', $this->subject);
    }
}
