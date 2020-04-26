<?php

declare(strict_types=1);

namespace Voodooism\Stega\Container;

use InvalidArgumentException;
use RuntimeException;
use Voodooism\Stega\Container\Image\Pixel;

class ImageContainer extends AbstractContainer
{
    /**
     * @var resource
     */
    private $resource;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var string
     */
    private $imageName;

    /**
     * ImageContainer constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        if (!is_file($path)) {
            throw new RuntimeException('You should provide correct path to your image');
        }

        $this->imageName = basename($path);

        $this->resource = imagecreatefromstring(
            file_get_contents($path)
        );

        $this->width = imagesx($this->resource);
        $this->height = imagesy($this->resource);
    }

    /**
     * @return int
     */
    public function getCapacity(): int
    {
        $capacity = $this->height * $this->width * 3;

        return $capacity;
    }

    /**
     * @inheritDoc
     */
    public function injectMessage(array $bits): void
    {
        $pixels = array_chunk($bits, 3);

        foreach ($pixels as $number => $lastBits) {
            $pixel = new Pixel($this->resource, $this->width, $number);

            $pixel->modifyPixel(...$lastBits);
        }
    }

    /**
     * @inheritDoc
     */
    public function extractLastSignificantBits(int $start, int $count): array
    {
        $firstPixel = (int)floor($start / 3);
        $lastPixel = (int)ceil(($start + $count) / 3);

        $lastSignificantBits = [];
        for ($i = $firstPixel; $i <= $lastPixel; $i++) {
            $pixel = new Pixel($this->resource, $this->width, $i);

            $lastSignificantBits[] = $pixel->getLastSignificantBits();
        }

        $lastSignificantBits = array_slice(
            array_merge(...$lastSignificantBits),
            $start - $firstPixel * 3,
            $count
        );

        return $lastSignificantBits;
    }

    /**
     * @inheritDoc
     */
    public function saveResource(string $path): void
    {
        if (!is_dir($path)) {
            throw new InvalidArgumentException('Wrong path');
        }

        $result = imagepng(
            $this->resource,
            sprintf(
                '%s/encoded_%s',
                $path,
                $this->imageName
            )
        );

        if ($result === false) {
            throw new RuntimeException('Can not save image');
        }
    }
}