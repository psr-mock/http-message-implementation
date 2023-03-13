<?php

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use PsrMock\Psr7\Request;
use PsrMock\Psr7\Stream;

it('should implement the MessageInterface', function () {
    $message = new Request();

    expect($message)
        ->toBeInstanceOf(MessageInterface::class);
});

it('should allow setting the protocol version', function () {
    $message = new Request();
    $message = $message->withProtocolVersion('1.1');

    expect($message->getProtocolVersion())
        ->toBe('1.1');
});

it('should not allow setting the protocol version using an invalid value', function () {
    $message = new Request();
    $message = $message->withProtocolVersion([8675309]);
})->throws(\InvalidArgumentException::class);

it('should have no headers by default', function () {
    $message = new Request();

    expect($message->getHeaders())
        ->toBe([]);
});

it('should allow setting headers', function () {
    $message = new Request();
    $message = $message->withHeader('Content-Type', 'text/plain');

    expect($message->getHeaders())
        ->toBe(['Content-Type' => ['text/plain']]);
});

it('should allow setting multiple headers using withHeader()', function () {
    $message = new Request();
    $message = $message->withHeader('Content-Type', ['text/plain', 'charset=utf-8']);

    expect($message->getHeaders())
        ->toBe(['Content-Type' => ['text/plain', 'charset=utf-8']]);
});

it('should allow setting multiple headers using withHeaders()', function () {
    $message = new Request();
    $message = $message->withHeaders(['Content-Type' => ['text/plain', 'charset=utf-8']]);

    expect($message->getHeaders())
        ->toBe(['Content-Type' => ['text/plain', 'charset=utf-8']]);

    $message = new Request();
    $message = $message->withHeaders(['text/plain', 'charset=utf-8']);

    expect($message->getHeaders())
        ->toBe([]);

    $message = new Request();
    $message = $message->withHeaders(['Content-Type' => 'text/plain']);

    expect($message->getHeaders())
        ->toBe(['Content-Type' => ['text/plain']]);
});

it('should allow getting headers', function () {
    $message = new Request();
    $message = $message->withHeader('Content-Type', 'text/plain');

    expect($message->getHeader('Content-Type'))
        ->toBe(['text/plain']);
});

it('should allow adding headers', function () {
    $message = new Request();
    $message = $message->withAddedHeader('Content-Type', 'text/plain');
    $message = $message->withAddedHeader('Content-Type', 'charset=utf-8');

    expect($message->getHeaders())
        ->toBe(['Content-Type' => ['text/plain', 'charset=utf-8']]);

    expect($message->getHeaderLine('Content-Type'))
        ->toBe('text/plain, charset=utf-8');

    $message = new Request();
    $message = $message->withAddedHeader('Content-Type', ['text/plain', 'charset=utf-8']);

    expect($message->getHeaders())
        ->toBe(['Content-Type' => ['text/plain', 'charset=utf-8']]);
});

it('should allow removing headers', function () {
    $message = new Request();
    $message = $message->withHeader('Content-Type', 'text/plain');
    $message = $message->withoutHeader('Content-Type');

    expect($message->getHeaders())
        ->toBe([]);
});

it('should allow checking on headers', function () {
    $message = new Request();

    expect($message->hasHeader('Content-Type'))
        ->toBeFalse();

    $message = $message->withHeader('Content-Type', 'text/plain');

    expect($message->hasHeader('Content-Type'))
        ->toBeTrue();

    $message = $message->withoutHeader('Content-Type');

    expect($message->hasHeader('Content-Type'))
        ->toBeFalse();
});

it('should have no body by default', function () {
    $message = new Request();

    expect((string) $message->getBody())
        ->toBe('');
});

it('should allow setting the body', function () {
    $message = new Request();
    $resource = new Stream('Hello, World!');
    $message = $message->withBody($resource);

    expect((string) $message->getBody())
        ->toBe('Hello, World!');
});

it('should allow getting the body as a stream', function () {
    $message = new Request();

    expect($message->getBody())
        ->toBeInstanceOf(StreamInterface::class);

    $stream = $message->getBody();

    expect($message->withBody($stream)->getBody())
        ->toBe($stream);
});
