<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic, Inc.
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\ChannelBundle\Form\Type;

use Mautic\ChannelBundle\Entity\Goal;
use Mautic\CoreBundle\Form\EventListener\CleanFormSubscriber;
use Mautic\CoreBundle\Form\Type\PropertiesTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoalType extends AbstractType
{
    use PropertiesTrait;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label'      => 'mautic.core.name',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => ['class' => 'form-control'],
            ]
        );

        $builder->add(
            'goalType',
            HiddenType::class
        );

        $builder->add(
            'order',
            HiddenType::class
        );

        $formModifier = function (FormEvent $event) use ($options, $builder) {
            /** @var Goal $data */
            $data = $event->getData();
            if (null === $data) {
                return;
            }

            $form = $event->getForm();
            $type = is_array($data) ? $data['goalType'] : $data->getGoalType();

            if (!empty($options['goals'][$type]['formType'])) {
                $options['settings'] = $options['goals'][$type];
                $options['data']     = $data;
                $masks               = [];
                $this->addPropertiesType($form, $options, $masks);

                if (!empty($masks)) {
                    $builder->addEventSubscriber(new CleanFormSubscriber($masks));
                }
            }
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, $formModifier);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, $formModifier);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['goals']);
        $resolver->setDefaults(
            [
                'data_class' => Goal::class,
            ]
        );
    }
}
