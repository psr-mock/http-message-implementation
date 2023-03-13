<?php

use PsrMock\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

it('should implement the ResponseInterface', function () {
    $response = new Response();

    expect($response)
        ->toBeInstanceOf(ResponseInterface::class);
});

it('should have a status code of 200 by default', function () {
    $response = new Response();

    expect($response->getStatusCode())
        ->toBe(200);
});

it('should allow setting the status code', function () {
    $response = new Response();
    $response = $response->withStatus(404);

    expect($response->getStatusCode())
        ->toBe(404);
});

it('should throw an error when an invalid status code is assigned', function () {
    $response = new Response();
    $response = $response->withStatus('testing');
})->throws(\InvalidArgumentException::class, 'Status code must be an integer');

it('should have an empty reason phrase by default', function () {
    $response = new Response();

    expect($response->getReasonPhrase())
        ->toBe('');
});

it('should allow setting the reason phrase', function () {
    $response = new Response();
    $response = $response->withStatus(404, 'Not Found');

    expect($response->getReasonPhrase())
        ->toBe('Not Found');
});

it('should throw an error when an invalid reason phrase is assigned', function () {
    $response = new Response();
    $response = $response->withStatus(200, ['testing']);
})->throws(\InvalidArgumentException::class, 'Reason phrase must be a string');

it('should have no headers by default', function () {
    $response = new Response();

    expect($response->getHeaders())
        ->toBe([]);
});

it('should allow setting headers', function () {
    $response = new Response();
    $response = $response->withHeader('Content-Type', 'text/plain');

    expect($response->getHeaders())
        ->toBe(['Content-Type' => ['text/plain']]);
});

it('should allow adding headers', function () {
    $response = new Response();
    $response = $response->withAddedHeader('Content-Type', 'text/plain');
    $response = $response->withAddedHeader('Content-Type', 'charset=utf-8');

    expect($response->getHeaders())
        ->toBe(['Content-Type' => ['text/plain', 'charset=utf-8']]);
});

it('should allow removing headers', function () {
    $response = new Response();
    $response = $response->withHeader('Content-Type', 'text/plain');
    $response = $response->withoutHeader('Content-Type');

    expect($response->getHeaders())
        ->toBe([]);
});

it('should have no body by default', function () {
    $response = new Response();

    expect((string) $response->getBody())
        ->toBe('');
});

it('should allow setting the body', function () {
    $response = new Response();
    $response->getBody()->write('Hello, World!');

    expect((string) $response->getBody())
        ->toBe('Hello, World!');
});
