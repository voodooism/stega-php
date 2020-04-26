<?php

declare(strict_types=1);

namespace Voodooism\Stega\Encoder;

use Voodooism\Stega\Container\AbstractContainer;
use Voodooism\Stega\Message\AbstractMessage;

abstract class AbstractEncoder
{
    /**
     * @param AbstractContainer $container
     * @param AbstractMessage   $message
     */
    abstract public function encode(AbstractContainer $container, AbstractMessage $message): void;

    /**
     * @param AbstractContainer $container
     *
     * @return AbstractMessage
     */
    abstract public function decode(AbstractContainer $container): AbstractMessage;
}