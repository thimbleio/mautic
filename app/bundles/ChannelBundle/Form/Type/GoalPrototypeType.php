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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GoalPrototypeType.
 */
class GoalPrototypeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['goals'] as $goalType => $goal) {
            $builder->add(
                str_replace('.', '-', $goalType),
                GoalType::class,
                [
                    'label' => $options['goals'][$goalType]['label'],
                    'goals' => $options['goals'],
                    'data'  => (new Goal())->setGoalType($goalType),
                ]
            );
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['goals']);

        // Disable validation for prototype forms
        $resolver->setDefault('validation_groups', false);
    }
}
