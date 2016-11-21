<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic, Inc.
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\ChannelBundle\Helper;

use Mautic\ChannelBundle\ChannelEvents;
use Mautic\ChannelBundle\Event\ChannelEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ChannelListHelper.
 */
class ChannelListHelper
{
    /**
     * @var ChannelEvent
     */
    protected $event;

    /**
     * ChannelListHelper constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->event = $dispatcher->dispatch(ChannelEvents::ADD_CHANNEL, new ChannelEvent());
    }

    /**
     * Get contact channels.
     *
     * @return array
     */
    public function getAllChannels()
    {
        return $this->event->getChannels();
    }

    /**
     * @param $features
     *
     * @return array
     */
    public function getFeatureChannels($features)
    {
        if (!is_array($features)) {
            return $this->event->getFeatureChannels($features);
        }

        $channels = [];
        foreach ($features as $feature) {
            $channels[$feature] = $this->event->getFeatureChannels($feature);
        }

        return $channels;
    }
}
