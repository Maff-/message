<?php

namespace spec\Http\Message\Encoding;

use Psr\Http\Message\StreamInterface;
use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;

class DechunkStreamSpec extends ObjectBehavior
{
    use StreamBehavior;

    function let(StreamInterface $stream)
    {
        if (defined('HHVM_VERSION')) {
            throw new SkippingException('Skipping test as there is no dechunk filter on hhvm');
        }

        $this->beConstructedWith($stream);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Http\Message\Encoding\DechunkStream');
    }

    function it_reads()
    {
        $stream = new MemoryStream("4\r\ntest\r\n4\r\ntest\r\n0\r\n\r\n\0");
        $this->beConstructedWith($stream);

        $this->read(6)->shouldReturn('testte');
        $this->read(6)->shouldReturn('st');
    }

    function it_gets_content()
    {
        $stream = new MemoryStream("4\r\ntest\r\n0\r\n\r\n\0");
        $this->beConstructedWith($stream);

        $this->getContents()->shouldReturn('test');
    }
}
