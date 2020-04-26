<?php

declare(strict_types=1);

namespace Voodooism\Stega\Message;

class TextMessage extends AbstractMessage
{
    /**
     * @var int
     */
    private const BITS_IN_SYMBOL = 8;

    /**
     * @var int[]|null
     */
    private $binary;

    /**
     * @var int|null
     */
    private $binaryLength;

    /**
     * @var string
     */
    private $string;

    /**
     * TextMessage constructor.
     *
     * @param string $string
     */
    public function __construct(string $string)
    {
        $this->string = $string;
    }

    /**
     * @param int[] $bits
     *
     * @return AbstractMessage
     */
    public static function createFromBits(array $bits): AbstractMessage
    {
        $binarySymbols = array_chunk($bits, self::BITS_IN_SYMBOL);

        $string = '';
        foreach ($binarySymbols as $binarySymbolList) {
            $binarySymbol = implode('', $binarySymbolList);

            $string .= pack(
                'H*',
                dechex((int)bindec($binarySymbol))
            );
        }

        $message = new self($string);

        return $message;
    }

    /**
     * @inheritDoc
     */
    public function getBinary(): array
    {
        if (!$this->binary) {
            /** @var string[] $characters */
            $characters = mb_str_split($this->string);

            $binaryArray = [];
            foreach ($characters as $key => $character) {
                $data = unpack('H*', $character);
                $binaryCharacter = base_convert($data[1], 16, 2);
                $binaryString = str_pad($binaryCharacter, 8, '0', STR_PAD_LEFT);

                foreach (str_split($binaryString) as $bitAsString) {
                    $binaryArray[] = (int)$bitAsString;
                }
            }

            $this->binary = $binaryArray;
        }

        return $this->binary;
    }

    /**
     * @return string
     */
    public function getString(): string
    {
        return $this->string;
    }

    /**
     * @return int
     */
    public function getBinaryLength(): int
    {
        if ($this->binaryLength === null) {
            $this->binaryLength = count($this->getBinary());
        }

        return $this->binaryLength;
    }
}