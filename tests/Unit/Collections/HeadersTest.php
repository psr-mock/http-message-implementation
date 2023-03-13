<?php

use PsrMock\Psr7\Entities\Header;
use PsrMock\Psr7\Collections\Headers;

beforeEach(function () {
    $this->headers = new Headers();
});

it('should allow adding headers', function () {
    $name = 'Content-Type';
    $value = 'application/json';

    $this->headers->add($name, $value);

    expect($this->headers->get($name))
        ->toBe([$value]);
});

it('should allow adding headers by entity', function () {
    $name = 'Content-Type';
    $value = 'application/json';

    $header = new Header($name, $value);
    $this->headers->addHeader($header);

    expect($this->headers->get($name))
        ->toBe([$value]);

    expect($this->headers->getHeader($name)[0])
        ->getName()->toBe($name)
        ->getValue()->toBe($value);
});

it('should allow setting headers', function () {
    $name = 'Content-Type';
    $value = 'application/json';

    $this->headers->set($name, $value);
    expect($this->headers->get($name))
        ->toBe([$value]);

    $this->headers->setHeader(new Header($name, $value . '2'));
    expect($this->headers->get($name))
        ->toBe([$value . '2']);
});

it('should allow getting headers', function () {
    $name = 'Content-Type';
    $value = 'application/json';
    $header = new Header($name, $value);

    $this->headers->addHeader($header);

    expect($this->headers->get($name))
        ->toBe([$value]);
});

it('should allow removing headers', function () {
    $name = 'Content-Type';
    $value = 'application/json';
    $header = new Header($name, $value);

    $this->headers->addHeader($header);
    $this->headers->remove($name);

    expect($this->headers->get($name))
        ->toBe([]);
});

it('should return all headers', function () {
    $headers = [
        new Header('Content-Type', 'application/json'),
        new Header('X-Api-Key', '12345'),
        new Header('Accept-Language', 'en'),
    ];

    $expect = [];

    foreach($headers as $header) {
        $this->headers->addHeader($header);
        $expect[$header->getName()] ??= [];
        $expect[$header->getName()][] = $header->getValue();
    }

    expect($this->headers->all())
        ->toBe($expect);
});
