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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ClassMetadata;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CategoryBundle\Entity\Category;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\FormEntity;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata as ValidationClassMetadata;

/**
 * Class Message.
 */
class Message extends FormEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $publishUp;

    /**
     * @var \DateTime
     */
    private $publishDown;

    /**
     * @var Category
     */
    private $category;

    /**
     * @var ArrayCollection
     */
    private $channels;

    /**
     * @var ArrayCollection
     */
    private $stats;

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('messages')
                ->setCustomRepositoryClass(MessageRepository::class)
                ->addIndex(['date_added'], 'date_message_added');

        $builder
            ->addIdColumns()
            ->addPublishDates()
            ->addCategory();
        $builder->createOneToMany('channels', Channel::class)
                ->setIndexBy('channel')
                ->orphanRemoval()
                ->mappedBy('message')
                ->cascadeMerge()
                ->cascadePersist()
                ->cascadeDetach()
                ->build();
        $builder->createOneToMany('stats', Stat::class)
                ->mappedBy('message')
                ->orphanRemoval()
                ->cascadeMerge()
                ->cascadePersist()
                ->cascadeDetach()
                ->fetchExtraLazy()
                ->build();
    }

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ValidationClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new NotBlank([
            'message' => 'mautic.core.name.required',
        ]));
    }

    /**
     * Prepares the metadata for API usage.
     *
     * @param $metadata
     */
    public static function loadApiMetadata(ApiMetadataDriver $metadata)
    {
        $metadata->setGroupPrefix('message')
            ->addListProperties(
                [
                    'id',
                    'name',
                    'description',
                ]
            )
            ->addProperties(
                [
                    'dateAdded',
                    'publishUp',
                    'publishDown',
                    'channels',
                ]
            )
            ->build();
    }

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->channels = new ArrayCollection();
        $this->stats    = new ArrayCollection();
    }

    /**
     * On clone.
     */
    public function __clone()
    {
        parent::__clone();

        $this->stats = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Message
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Message
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPublishUp()
    {
        return $this->publishUp;
    }

    /**
     * @param \DateTime $publishUp
     *
     * @return Message
     */
    public function setPublishUp($publishUp)
    {
        $this->publishUp = $publishUp;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPublishDown()
    {
        return $this->publishDown;
    }

    /**
     * @param \DateTime $publishDown
     *
     * @return Message
     */
    public function setPublishDown($publishDown)
    {
        $this->publishDown = $publishDown;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     *
     * @return Message
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * @param ArrayCollection $channels
     *
     * @return Message
     */
    public function setChannels($channels)
    {
        $this->channels = $channels;

        return $this;
    }

    /**
     * @param Channel $channel
     */
    public function addChannel(Channel $channel)
    {
        if (!$this->channels->contains($channel)) {
            $this->isChanged('channels', $channel);

            $this->channels[$channel->getChannel()] = $channel;
        }
    }

    /**
     * @param Channel $channel
     */
    public function removeChannel(Channel $channel)
    {
        if ($channel->getId()) {
            $this->isChanged('channels', $channel->getId());
        }
        $this->channels->removeElement($channel);
    }

    /**
     * @return ArrayCollection
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * @param ArrayCollection $stats
     *
     * @return Message
     */
    public function setStats($stats)
    {
        $this->stats = $stats;

        return $this;
    }

    /**
     * @param Stat $stat
     */
    public function addStat(Stat $stat)
    {
        $this->stats[] = $stat;
    }

    /**
     * @param Stat $stat
     */
    public function removeStat(Stat $stat)
    {
        $this->stats->removeElement($stat);
    }
}
