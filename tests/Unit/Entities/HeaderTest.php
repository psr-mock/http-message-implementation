<?php

use PsrMock\Psr7\Entities\Header;

it('should store the header name and value', function () {
    $name = 'Content-Type';
    $value = 'application/json';
    $header = new Header($name, $value);

    expect($header->getName())->toBe($name);
    expect($header->getValue())->toBe($value);
});

it('should allow updating the header value', function () {
    $name = 'Content-Type';
    $value = 'application/json';
    $header = new Header($name, $value);

    $newValue = 'text/html';
    $header->setValue($newValue);

    expect($header->getValue())->toBe($newValue);
});

it('should implement the Stringable interface', function () {
    $name = 'Content-Type';
    $value = 'application/json';
    $header = new Header($name, $value);

    expect(strval($header))->toBe("$name: $value");
});
