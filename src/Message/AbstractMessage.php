<?php

declare(strict_types=1);

namespace Voodooism\Stega\Message;

abstract class AbstractMessage
{
    /**
     * @param int[] $bit
     *
     * @return AbstractMessage
     */
    abstract public static function createFromBits(array $bit): AbstractMessage;

    /**
     * @return int[]
     */
    abstract public function getBinary(): array;

    /**
     * @return int
     */
    abstract public function getBinaryLength(): int;
}