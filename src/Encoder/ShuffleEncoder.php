<?php

declare(strict_types=1);

namespace Voodooism\Stega\Encoder;

use Exception;
use ParagonIE\SeedSpring\SeedSpring;
use Voodooism\Stega\Container\AbstractContainer;
use Voodooism\Stega\Container\Header;
use Voodooism\Stega\Message\AbstractMessage;
use Voodooism\Stega\Message\MessageFactory;

class ShuffleEncoder extends AbstractEncoder
{
    /**
     * @var SeedSpring
     */
    private $prng;

    /**
     * @var array
     */
    private $usedKeys = [];

    /**
     * ShuffleEncoder constructor.
     *
     * @param string $password
     */
    public function __construct(string $password)
    {
        $seed = md5($password, true);

        $this->prng = new SeedSpring($seed);
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function encode(AbstractContainer $container, AbstractMessage $message): void
    {
        $header = new Header($container->getType(), $message->getBinaryLength());

        $bits = array_merge($header->getBinary(), $message->getBinary());

        $shuffle = $this->shuffle(count($bits), $container->getCapacity());

        $shuffledBits = array_combine($shuffle, $bits);

        $container->injectMessage($shuffledBits);
    }

    /**
     * @inheritDoc
     */
    public function decode(AbstractContainer $container): AbstractMessage
    {
        $lastSignificantBits = $container->extractLastSignificantBits(0, $container->getCapacity());

        $headerShuffle = $this->shuffle(Header::HEADER_SIZE, $container->getCapacity());

        $header = Header::createFromBinary(
            $this->extractShuffled($lastSignificantBits, $headerShuffle)
        );

        $messageShuffle = $this->shuffle($header->getMessageLength(), $container->getCapacity());

        $message = MessageFactory::createMessage(
            $header->getType(),
            $this->extractShuffled($lastSignificantBits, $messageShuffle)
        );

        return $message;
    }

    /**
     * @param int $count
     *
     * @param int   $capacity
     *
     * @return int[]
     * @throws Exception
     */
    public function shuffle(int $count, int $capacity): array
    {
        $result = [];
        $current = 0;

        while($current < $count) {
            $key = $this->prng->getInt(0, $capacity);

            if (!isset($this->usedKeys[$key])) {
                $this->usedKeys[$key] = true;
                $result[$key] = true;
                $current++;
            }
        }

        $result = array_keys($result);

        return $result;
    }

    /**
     * @param int[] $array
     * @param int[] $shuffledKeys
     *
     * @return int[]
     */
    private function extractShuffled(array $array, array $shuffledKeys): array
    {
        $result = [];

        foreach ($shuffledKeys as $key) {
            $result[] = $array[$key];
        }

        return $result;
    }
}