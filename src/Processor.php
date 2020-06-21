<?php

declare(strict_types=1);

namespace Voodooism\Stega;

use Voodooism\Stega\Container\AbstractContainer;
use Voodooism\Stega\Encoder\AbstractEncoder;
use Voodooism\Stega\Message\AbstractMessage;

class Processor
{
    /**
     * @var AbstractEncoder
     */
    private $encoder;

    /**
     * Processor constructor.
     *
     * @param AbstractEncoder $encoder
     */
    public function __construct(AbstractEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param AbstractContainer $container
     * @param AbstractMessage   $message
     *
     * @return void
     */
    public function encode(AbstractContainer $container, AbstractMessage $message): void
    {
        $this->encoder->encode($container, $message);
    }

    /**
     * @param AbstractContainer $container
     *
     * @return AbstractMessage
     */
    public function decode(AbstractContainer $container): AbstractMessage
    {
        $message = $this->encoder->decode($container);

        return $message;
    }
}