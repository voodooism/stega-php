<?php

declare(strict_types=1);

namespace Voodooism\Stega\Container;

class Header
{
    /** @var int  */
    public const HEADER_SIZE = self::INT_BIT_SIZE * 2;

    /** @var int  */
    private const INT_BIT_SIZE = PHP_INT_SIZE * 8;

    /**
     * @var int
     */
    private $messageLength;

    /**
     * @var int
     */
    private $type;

    /**
     * Header constructor.
     *
     * @param int $messageLength
     * @param int $type
     */
    public function __construct(int $type, int $messageLength)
    {
        $this->type          = $type;
        $this->messageLength = $messageLength;
    }

    /**
     * @param int[] $binary
     *
     * @return self
     */
    public static function createFromBinary(array $binary): self
    {
        $type = implode('', array_slice($binary, 0, self::INT_BIT_SIZE));
        $length = implode('', array_slice($binary, self::INT_BIT_SIZE, self::INT_BIT_SIZE));

        $header = new self(
            (int)bindec($type),
            (int)bindec($length)
        );

        return $header;
    }

    /**
     * @return int
     */
    public function getMessageLength(): int
    {
        return $this->messageLength;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return int[]
     */
    public function getBinary(): array
    {
        $binaryString = $this->convertIntToBinaryString($this->type) . $this->convertIntToBinaryString($this->messageLength);

        $binaryResult = [];
        foreach (str_split($binaryString) as $bit) {
            $binaryResult[] = (int)$bit;
        }

        return $binaryResult;
    }

    /**
     * @param int $int
     *
     * @return string
     */
    private function convertIntToBinaryString(int $int): string
    {
        $binaryString = str_pad(decbin($int), self::INT_BIT_SIZE, '0', STR_PAD_LEFT);

        return $binaryString;
    }
}