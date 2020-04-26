<?php

declare(strict_types=1);

namespace Voodooism\Stega\Container\Image;

class Pixel
{
    /**
     * @var int
     */
    private $b;

    /**
     * @var int
     */
    private $g;

    /**
     * @var int
     */
    private $r;

    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * @var resource
     */
    private $resource;

    /**
     * Pixel constructor.
     *
     * @param resource $resource
     * @param int      $width
     * @param int      $number
     */
    public function __construct($resource, int $width, int $number)
    {
        $x = $number % $width;
        $y = (int)floor($number / $width);

        $rgb = imagecolorat($resource, $x, $y);

        $this->resource = $resource;

        $this->r = ($rgb >> 16) & 0xFF;
        $this->g = ($rgb >> 8) & 0xFF;
        $this->b = $rgb & 0xFF;

        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @param int $r
     * @param int $g
     * @param int $b
     */
    public function modifyPixel(?int $r = null, ?int $g = null, ?int $b = null): void
    {
        $this->r = $r !== null ? $this->modifyLastSignificantBit($this->r, $r) : $this->r;
        $this->g = $g !== null ? $this->modifyLastSignificantBit($this->g, $g) : $this->g;
        $this->b = $b !== null ? $this->modifyLastSignificantBit($this->b, $b) : $this->b;

        $color = imagecolorallocate($this->resource, $this->r, $this->g, $this->b);
        imagesetpixel($this->resource, $this->x, $this->y, $color);
    }

    /**
     * @param int $value
     * @param int $bit
     *
     * @return int
     */
    public function modifyLastSignificantBit(int $value, int $bit): int
    {
        $binaryString = decbin($value);

        $lsbChanged = substr_replace($binaryString, (string)$bit, strlen($binaryString) - 1);

        $integer = (int)bindec($lsbChanged);

        return $integer;
    }

    /**
     * @return int[]
     */
    public function getLastSignificantBits(): array
    {
        return [
            (($this->r >> 0) & 1),
            (($this->g >> 0) & 1),
            (($this->b >> 0) & 1)
        ];
    }
}