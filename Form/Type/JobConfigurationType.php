<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\Bundle\JobQueueBundle\Form\Type;

use Aureja\Bundle\JobQueueBundle\Form\Subscriber\AddJobFactorySubscriber;
use Aureja\Bundle\JobQueueBundle\Form\Subscriber\AddJobParametersSubscriber;
use Aureja\Bundle\JobQueueBundle\Util\LegacyFormHelper;
use Aureja\Bundle\JobQueueBundle\Validator\Constraints\UniqueJobConfiguration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 4/28/15 10:54 PM
 */
class JobConfigurationType extends AbstractType
{

    /**
     * @var AddJobFactorySubscriber
     */
    private $factorySubscriber;

    /**
     * @var AddJobParametersSubscriber
     */
    private $parametersSubscriber;

    /**
     * @var array
     */
    private $queues;

    /**
     * @var string
     */
    private $configurationClass;

    /**
     * Constructor.
     *
     * @param AddJobFactorySubscriber $factorySubscriber
     * @param AddJobParametersSubscriber $parametersSubscriber
     * @param array $queues
     * @param string $configurationClass
     */
    public function __construct(
        AddJobFactorySubscriber $factorySubscriber,
        AddJobParametersSubscriber $parametersSubscriber,
        array $queues,
        $configurationClass
    ) {
        $this->factorySubscriber = $factorySubscriber;
        $this->parametersSubscriber = $parametersSubscriber;
        $this->queues = $queues;
        $this->configurationClass = $configurationClass;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\TextType'),
                [
                    'label' => 'name',
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => 3, 'max' => 255])
                    ],
                ]
            )
            ->add(
                'enabled',
                LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\CheckboxType'),
                [
                    'label' => 'enabled',
                    'required' => false,
                ]
            )
            ->add(
                'autoRestorable',
                LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\CheckboxType'),
                [
                    'label' => 'auto_restorable',
                    'required' => false,
                ]
            )
            ->add(
                'period',
                LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\IntegerType'),
                [
                    'label' => 'period',
                    'constraints' => new Assert\NotBlank(),
                ]
            )
            ->add(
                'queue',
                LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\ChoiceType'),
                [
                    'label' => 'queue',
                    'choices' => $this->getQueueChoices(),
                    (LegacyFormHelper::isLegacy() ? 'empty_value' : 'placeholder') => 'select',
                    'constraints' => new Assert\NotBlank(),
                ]
            )
            ->add(
                'submit',
                LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'),
                [
                    'label' => 'save'
                ]
            );

        $builder->addEventSubscriber($this->factorySubscriber);
        $builder->addEventSubscriber($this->parametersSubscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'constraints' => new UniqueJobConfiguration(),
                'data_class' => $this->configurationClass,
                'translation_domain' => 'AurejaJobQueue',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'aureja_job_configuration';
    }

    /**
     * @return array
     */
    private function getQueueChoices()
    {
        return array_combine($this->queues, $this->queues);
    }
}
