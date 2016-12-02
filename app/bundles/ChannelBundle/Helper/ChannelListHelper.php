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
    public function getChannelList()
    {
        if (!count($this->channels)) {
            $allChannels = $this->event->getChannels();
            $channels    = [];
            foreach ($allChannels as $channel => $details) {
                $channelName            = isset($details['label']) ? $this->translator->trans($details['label']) : $this->getChannelLabel($channel);
                $channels[$channelName] = $channel;
            }
            $this->channels = $channels;
        }

        return $this->channels;
    }

    /**
     * @param      $features
     * @param bool $listOnly
     *
     * @return array
     */
    public function getFeatureChannels($features, $listOnly = false)
    {
        if (!is_array($features)) {
            $features = [$features];
        }

        $channels = [];
        foreach ($features as $feature) {
            $featureChannels = $this->event->getFeatureChannels($feature);
            $returnChannels  = [];
            foreach ($featureChannels as $channel => $details) {
                if (!isset($details['label'])) {
                    $featureChannels[$channel]['label'] = $this->getChannelLabel($channel);
                }

                if ($listOnly) {
                    $returnChannels[$featureChannels[$channel]['label']] = $channel;
                } else {
                    $returnChannels[$channel] = $featureChannels[$channel];
                }
            }
            unset($featureChannels);
            $channels[$feature] = $returnChannels;
        }

        if (count($features) === 1) {
            $channels = $channels[$features[0]];
        }

        return $channels;
    }

    /**
     * @return string
     */
    public function getChannels()
    {
        return $this->event->getChannels();
    }

    /**
     * @param $channel
     *
     * @return string
     */
    private function getChannelLabel($channel)
    {
        return $this->translator->hasId('mautic.channel.'.$channel) ?
            $this->translator->trans('mautic.channel.'.$channel) : ucfirst($channel);
    }
}
