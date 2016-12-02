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
use Symfony\Component\Translation\TranslatorInterface;

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
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var
     */
    protected $channels;

    /**
     * ChannelListHelper constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     */
    public function __construct(EventDispatcherInterface $dispatcher, TranslatorInterface $translator)
    {
        $this->event      = $dispatcher->dispatch(ChannelEvents::ADD_CHANNEL, new ChannelEvent());
        $this->translator = $translator;
    }

    /**
     * Get contact channels.
     *
     * @return array
     */
    public function getAllChannels()
    {
        if (count($this->channels)) {
            $allChannels = $this->event->getChannels();

            $channels = [];
            foreach ($allChannels as $channel) {
                $channelName = $this->translator->hasId('mautic.channel.'.$channel) ?
                    $this->translator->trans('mautic.channel.'.$channel) : ucfirst($channel);
                $channels[$channelName] = $channel;
            }
            $this->channels = $channels;
        }

        return $this->channels;
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
