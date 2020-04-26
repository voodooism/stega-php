<?php

declare(strict_types=1);

namespace Voodooism\Stega\Container;

use ReflectionClass;
use ReflectionException;

class ContainerTypeEnum
{
    public const IMAGE_TYPE = 0;

    /**
     * Returns all constants for class
     *
     * @return array
     * @throws ReflectionException
     */
    public static function all(): array
    {
        $oClass = new ReflectionClass(static::class);

        return $oClass->getConstants();
    }
}