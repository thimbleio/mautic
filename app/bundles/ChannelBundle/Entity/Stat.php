<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic, Inc.
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\ChannelBundle\Entity;

use Doctrine\ORM\Mapping\ClassMetadata;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\CommonEntity;
use Mautic\LeadBundle\Entity\Lead;

class Stat extends CommonEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Message
     */
    private $message;

    /**
     * @var Lead
     */
    private $contact;

    /**
     * @var Channel
     */
    private $channel;

    /**
     * @var \DateTime
     */
    private $dateSent;

    /**
     * @var int
     */
    private $channelStatId;

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('message_stats')
            ->addIndex(['date_sent'], 'message_sent_index')
            ->addIndex(['channel_stat_id'], 'message_channel_stat_index');

        $builder
            ->addId()
            ->addContact()
            ->addNamedField('dateSent', 'datetime', 'date_sent')
            ->addNamedField('channelStatId', 'integer', 'channel_stat_id')
            ->createManyToOne('message', Message::class, 'stats')
                ->addJoinColumn('message_id', 'id', false, false, 'CASCADE')
                ->inversedBy('stats')
                ->build();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param Message $message
     *
     * @return Stat
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Lead
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param Lead $contact
     *
     * @return Stat
     */
    public function setContact(Lead $contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param Channel $channel
     *
     * @return Stat
     */
    public function setChannel(Channel $channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateSent()
    {
        return $this->dateSent;
    }

    /**
     * @param \DateTime $dateSent
     *
     * @return Stat
     */
    public function setDateSent($dateSent)
    {
        $this->dateSent = $dateSent;

        return $this;
    }

    /**
     * @return int
     */
    public function getChannelStatId()
    {
        return $this->channelStatId;
    }

    /**
     * @param int $channelStatId
     *
     * @return Stat
     */
    public function setChannelStatId($channelStatId)
    {
        $this->channelStatId = $channelStatId;

        return $this;
    }
}
