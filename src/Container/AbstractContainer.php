<?php

declare(strict_types=1);

namespace Voodooism\Stega\Container;

abstract class AbstractContainer
{
    /**
     * @return int
     */
    abstract public function getCapacity(): int;

    /**
     * @param int[] $bits
     */
    abstract public function injectMessage(array $bits): void;

    /**
     * @param int $start
     * @param int $count
     *
     * @return int[]
     */
    abstract public function extractLastSignificantBits(int $start, int $count): array;

    /**
     * @param string $path
     */
    abstract public function saveResource(string $path): void;
}