<?php

declare(strict_types=1);

namespace Voodooism\Stega\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Voodooism\Stega\Container\ImageContainer;
use Voodooism\Stega\Encoder\ShuffleEncoder;
use Voodooism\Stega\Encoder\SimpleEncoder;
use Voodooism\Stega\Message\TextMessage;
use Voodooism\Stega\Processor;

class ProcessorTest extends TestCase
{
    /**
     * @var string
     */
    private $fixturesPath;

    protected function setUp(): void
    {
       $this->fixturesPath = __DIR__ . '/../fixtures';

    }

    public function testImageContainerTextMessageSimpleEncoder(): void
    {
        $processor = new Processor(new SimpleEncoder());

        $processor->encode(
            new ImageContainer($containerPath = $this->fixturesPath . '/container.png'),
            new TextMessage($messageText = 'hello world'),
            $this->fixturesPath
        );

        $this->assertNotEquals(
            file_get_contents($containerPath),
            file_get_contents($this->fixturesPath . '/encoded_container.png')
        );

        $decodedMessage = $processor->decode(
            new ImageContainer($this->fixturesPath . '/encoded_container.png')
        );

        $this->assertEquals($messageText, $decodedMessage->getMessage());
    }

    public function testImageContainerTextMessageShuffleEncoder(): void
    {
        $processor = new Processor(new ShuffleEncoder('password'));

        $processor->encode(
            new ImageContainer($containerPath = $this->fixturesPath . '/container.png'),
            new TextMessage($messageText = 'hello world'),
            $this->fixturesPath
        );

        $this->assertNotEquals(
            file_get_contents($containerPath),
            file_get_contents($this->fixturesPath . '/encoded_container.png')
        );

        $newProcessor = new Processor(new ShuffleEncoder('password'));

        $decodedMessage = $newProcessor->decode(
            new ImageContainer($this->fixturesPath . '/encoded_container.png')
        );

        $this->assertEquals($messageText, $decodedMessage->getMessage());
    }
}