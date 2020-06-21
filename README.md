# Steganography.
The main task of steganography in images is to embed information in a digital image so that both the message and the fact of its presence are hidden. Unlike cryptography, the task of steganography is to hide the very fact of the presence of a hidden message. The resulting image with additional hidden information should not look abnormal. This is achieved by making changes that are invisible to human vision.

You can read more about steganography in [wikipedia](https://en.wikipedia.org/wiki/Steganography)

# Usage.

## Encoding:
First of all, you need to instantiate a processor,  then create a new image container using a path to your image file 
as a first argument and embed your text to `TextMessage` object. When that all objects will be instantiated you can 
call method `encode` of the processor. The `encode` method doesn't affect your existing file, so after encoding you need 
to call `saveResource` method of your container to save the encoded file.

```php
use Voodooism\Stega\Container\ImageContainer;
use Voodooism\Stega\Encoder\SimpleEncoder;
use Voodooism\Stega\Message\TextMessage;
use Voodooism\Stega\Processor;

$processor = new Processor(new SimpleEncoder());

$container   = new ImageContainer('/path/to/your/image/file');
$textMessage = new TextMessage('your message here');

$processor->encode($container, $textMessage);

$container->saveResource('/path/to/save/encoded/file');
```

## Decoding:
If you already have an encoded message, you need to instantiate the processor, create an ImageContainer as in the previous step, and
to call the `decode` with the encoded image container as an argument. The result will be a decoded message.

```php
use Voodooism\Stega\Container\ImageContainer;
use Voodooism\Stega\Encoder\SimpleEncoder;
use Voodooism\Stega\Processor;

$processor = new Processor(new SimpleEncoder());
$container = new ImageContainer('/path/to/encoded/image/file');

$decodedMessage = $processor->decode($container);
```

# Encoders type
Least Significant Bit steganography is one such technique in which the least significant bit of pixels of the image is
replaced with data bits. This approach has the advantage that it is the simplest one to understand, easy to implement, and
results in stego-images that contain embedded data as hidden. 

The disadvantage of Least Significant Bit is that it is vulnerable to steganalysis and is not super secure.

To solve the security problem there is `ShuffleEncoder`.

The difference between `SimpleEncoder` and `ShuffleEncoder` is the simple encoder writes message bits continuously
from the beginning of the file, but the shuffle encoder shuffles message bits using "password" as a source of entropy.
Thus, only you and the person who knows the "password" can decode the message.

To use advantages of the shuffle encoder you need to instantiate the processor by passing it the `ShuffleEncoder` with 
the password as the first argument

Example:
```php
use Voodooism\Stega\Encoder\ShuffleEncoder;
use Voodooism\Stega\Processor;

$processor = new Processor(new ShuffleEncoder('secret123'));
```