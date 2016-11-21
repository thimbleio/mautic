<?php
/**
 * Created by PhpStorm.
 * User: alan
 * Date: 10/31/16
 * Time: 8:43 AM.
 */

namespace Mautic\ChannelBundle\Entity;

use Doctrine\ORM\Mapping\ClassMetadata;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata as ValidationClassMetadata;

class Goal
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
    private $goalType;

    /**
     * @var Channel
     */
    private $channel;

    /**
     * @var array
     */
    private $properties = [];

    /**
     * @var int
     */
    private $order = 0;

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('message_goals')
            ->addIndex(['goal_type'], 'campaign_decisions');

        $builder->addIdColumns('name', false)
            ->addNamedField('goalType', 'string', 'goal_type')
            ->addNamedField('order', 'integer', 'goal_order')
            ->addField('properties', 'json_array')
            ->createManyToOne('channel', Channel::class, 'goals')
                ->inversedBy('goals')
                ->addJoinColumn('channel_id', 'id', false, false, 'CASCADE')
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
     * @return Goal
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getGoalType()
    {
        return $this->goalType;
    }

    /**
     * @param string $goalType
     *
     * @return Goal
     */
    public function setGoalType($goalType)
    {
        $this->goalType = $goalType;

        return $this;
    }

    /**
     * @return Channel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param Channel $channel
     *
     * @return Goal
     */
    public function setChannel(Channel $channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     *
     * @return Goal
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     *
     * @return Goal
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }
}
