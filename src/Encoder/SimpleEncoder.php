<?php

declare(strict_types=1);

namespace Voodooism\Stega\Encoder;

use Voodooism\Stega\Container\AbstractContainer;
use Voodooism\Stega\Container\Header;
use Voodooism\Stega\Message\AbstractMessage;
use Voodooism\Stega\Message\MessageFactory;

class SimpleEncoder extends AbstractEncoder
{
    /**
     * @inheritDoc
     */
    public function encode(AbstractContainer $container, AbstractMessage $message): void
    {
        $header = new Header($container->getType(), $message->getBinaryLength());

        $bits = array_merge($header->getBinary(), $message->getBinary());

        $container->injectMessage($bits);
    }

    /**
     * @inheritDoc
     */
    public function decode(AbstractContainer $container): AbstractMessage
    {
        $binaryHeader = $container->extractLastSignificantBits(0,Header::HEADER_SIZE);
        $header = Header::createFromBinary($binaryHeader);

        $binaryMessage = $container->extractLastSignificantBits(Header::HEADER_SIZE, $header->getMessageLength());

        $message = MessageFactory::createMessage($header->getType(), $binaryMessage);

        return $message;
    }
}