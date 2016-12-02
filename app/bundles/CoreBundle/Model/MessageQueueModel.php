<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\CoreBundle\Model;

@trigger_error('Mautic\CoreBundle\Model\MessageQueueModel was deprecated in 2.4 and to be removed in 3.0 Use \Mautic\ChannelBundle\Model\MessageQueueModel instead', E_DEPRECATED);

/**
 * Class MessageQueueModel.
 *
 * @deprecated 2.4; to be removed in 3.0
 */
class MessageQueueModel extends \Mautic\ChannelBundle\Model\MessageQueueModel
{
}
