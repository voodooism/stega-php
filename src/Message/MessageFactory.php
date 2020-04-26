<?php

declare(strict_types=1);

namespace Voodooism\Stega\Message;

use InvalidArgumentException;
use Voodooism\Stega\Container\ContainerTypeEnum;

abstract class MessageFactory
{
    /**
     * @param int   $type
     * @param int[] $binaryMessage
     *
     * @return AbstractMessage
     */
    public static function createMessage(int $type, array $binaryMessage): AbstractMessage
    {
        switch ($type) {
            case ContainerTypeEnum::IMAGE_TYPE :
                $message = TextMessage::createFromBits($binaryMessage);
                break;
            default:
                throw new InvalidArgumentException('Wrong message type');
        }

        return $message;
    }
}