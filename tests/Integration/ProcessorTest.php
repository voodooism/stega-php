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

    /**
     * @var string
     */
    private $container;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $encodedImage;

    protected function setUp(): void
    {
       $this->fixturesPath = __DIR__ . '/../fixtures';
       $this->container = __DIR__ . '/../fixtures/container.png';
       $this->message = __DIR__ . '/../fixtures/16kb.txt';
       $this->encodedImage = __DIR__ . '/../fixtures/encoded_container.png';
    }

    protected function tearDown(): void
    {
        if (file_exists($this->encodedImage)) {
            unlink($this->encodedImage);
        }
    }

    public function testImageContainerTextMessageSimpleEncoder(): void
    {
        $processor = new Processor(new SimpleEncoder());

        $processor->encode(
            new ImageContainer($this->container),
            new TextMessage($messageText = file_get_contents($this->message)),
            $this->fixturesPath
        );

        $this->assertNotEquals(
            file_get_contents($this->container),
            file_get_contents($this->encodedImage)
        );

        $decodedMessage = $processor->decode(
            new ImageContainer($this->encodedImage)
        );

        $this->assertEquals($messageText, $decodedMessage->getMessage());
    }

    public function testImageContainerTextMessageShuffleEncoder(): void
    {
        $processor = new Processor($encoder = new ShuffleEncoder('secret123'));

        $processor->encode(
            new ImageContainer($this->container),
            new TextMessage($messageText = file_get_contents($this->message)),
            $this->fixturesPath
        );

        $this->assertNotEquals(
            file_get_contents($this->container),
            file_get_contents($this->encodedImage)
        );

        $newProcessor = new Processor($decoder = new ShuffleEncoder('secret123'));

        $decodedMessage = $newProcessor->decode(
            new ImageContainer($this->encodedImage)
        );

        $this->assertEquals($messageText, $decodedMessage->getMessage());
    }
}