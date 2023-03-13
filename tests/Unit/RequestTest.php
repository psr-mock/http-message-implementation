<?php

use PsrMock\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use PsrMock\Psr7\Uri;

it('should implement the RequestInterface', function () {
    $request = new Request('GET', 'https://example.com');

    expect($request)
        ->toBeInstanceOf(RequestInterface::class);
});

it('should have a method of GET by default', function () {
    $request = new Request(uri: 'https://example.com');

    expect($request->getMethod())
        ->toBe('GET');
});

it('should allow setting the method', function () {
    $request = new Request(uri: 'https://example.com');
    $request = $request->withMethod('POST');

    expect($request->getMethod())
        ->toBe('POST');
});

it('should have an empty URI by default', function () {
    $request = new Request('GET');

    expect($request->getUri())
        ->toBeInstanceOf(UriInterface::class);

    expect((string) $request->getUri())
        ->toBe('');
});

it('should allow setting the URI', function () {
    $request = new Request('GET');
    $uri = 'https://example.com/path?query=value';
    $request = $request->withUri(new Uri($uri));

    expect((string) $request->getUri())
        ->toBe($uri);
});

it('should allow setting headers', function () {
    $request = new Request('GET');
    $request = $request->withHeader('Content-Type', 'text/plain');

    expect($request->getHeaders())
        ->toBe(['Content-Type' => ['text/plain']]);
});

it('should allow adding headers', function () {
    $request = new Request('GET');
    $request = $request->withAddedHeader('Content-Type', 'text/plain');
    $request = $request->withAddedHeader('Content-Type', 'charset=utf-8');

    expect($request->getHeaders())
        ->toBe(['Content-Type' => ['text/plain', 'charset=utf-8']]);
});

it('should allow removing headers', function () {
    $request = new Request('GET');
    $request = $request->withHeader('Content-Type', 'text/plain');
    $request = $request->withoutHeader('Content-Type');

    expect($request->getHeaders())
        ->toBe([]);
});

it('should have no body by default', function () {
    $request = new Request('GET');

    expect((string) $request->getBody())
        ->toBe('');
});

it('should allow setting the body', function () {
    $request = new Request('GET');
    $request->getBody()->write('Hello, World!');

    expect((string) $request->getBody())
        ->toBe('Hello, World!');
});

it('sets a request target', function () {
    $request = new Request('GET');

    expect($request->getRequestTarget())
        ->toBe('');

    $target = uniqid();
    $request = $request->withRequestTarget($target);

    expect($request->getRequestTarget())
        ->toBe($target);
});

it('throws an exception when an invalid request target is provided', function () {
    $request = new Request('GET');
    $request->withRequestTarget(8675309);
})->throws(\InvalidArgumentException::class);
