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

use Aureja\Bundle\JobQueueBundle\Form\Type\JobFactoryTypeMap;
use Aureja\JobQueue\Model\JobConfigurationInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 11/14/15 6:14 PM
 */
class AddJobParametersSubscriber implements EventSubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'addJobParametersField'
        ];
    }

    /**
     * Add job parameters field.
     *
     * @param FormEvent $event
     */
    public function addJobParametersField(FormEvent $event)
    {
        $data = $event->getData();

        if ($data instanceof JobConfigurationInterface && $data->getId()) {
            $form = $event->getForm();

            $form->add('parameters', JobFactoryTypeMap::getType($data->getFactory()));
        }
    }
}
