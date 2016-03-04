<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\Bundle\JobQueueBundle\Form\Subscriber;

use Aureja\JobQueue\Model\JobConfigurationInterface;
use Aureja\JobQueue\Provider\JobProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 11/14/15 7:46 PM
 */
class AddJobFactorySubscriber implements EventSubscriberInterface
{

    /**
     * @var JobProviderInterface
     */
    private $jobProvider;

    /**
     * Constructor.
     *
     * @param JobProviderInterface $jobProvider
     */
    public function __construct(JobProviderInterface $jobProvider)
    {
        $this->jobProvider = $jobProvider;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'addJobFactoryField'
        ];
    }

    /**
     * Add job factory field.
     *
     * @param FormEvent $event
     */
    public function addJobFactoryField(FormEvent $event)
    {
        $data = $event->getData();

        if (($data instanceof JobConfigurationInterface) && (null === $data->getId())) {
            $form = $event->getForm();

            $form->add(
                'factory',
                ChoiceType::class,
                [
                    'label' => 'job_type',
                    'choices' => $this->getFactoryNameChoices(),
                    'placeholder' => 'select',
                    'constraints' => new Assert\NotBlank(),
                ]
            );
        }
    }

    /**
     * @return array
     */
    private function getFactoryNameChoices()
    {
        $names = $this->jobProvider->getFactoryNames();

        return array_combine($names, $names);
    }
}
