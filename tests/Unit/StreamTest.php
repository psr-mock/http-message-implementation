<?php

use PsrMock\Psr7\Stream;

it('can get the stream contents', function () {
    $contents = 'Lorem ipsum dolor sit amet';
    $stream = new Stream($contents);

    expect($stream->getContents())
        ->toBe($contents);
});

it('can return the size of the stream', function () {
    $contents = 'Lorem ipsum dolor sit amet';
    $stream = new Stream($contents);

    expect($stream->getSize())
        ->toBe(strlen($contents));
});

it('can tell the current position of the stream', function () {
    $contents = 'Lorem ipsum dolor sit amet';
    $stream = new Stream($contents);

    expect($stream->read(6))
        ->toEqual('Lorem ');

    expect($stream->tell())
        ->toBe(6);
});

it('can check if the stream is at the end', function () {
    $contents = 'Lorem ipsum dolor sit amet';
    $stream = new Stream($contents);

    expect($stream->eof())
        ->toBeFalse();

    expect($stream->read(strlen($contents) + 1))
        ->toEqual($contents);

    expect($stream->eof())
        ->toBeTrue();
});

it('can rewind the stream', function () {
    $contents = 'Lorem ipsum dolor sit amet';
    $stream = new Stream($contents);

    expect($stream->read(6))
        ->toEqual('Lorem ');

    $stream->rewind();

    expect($stream->tell(0))
        ->toBe(0);
});

it('can read data from the stream', function () {
    $contents = 'Lorem ipsum dolor sit amet';
    $stream = new Stream($contents);

    expect($stream->read(11))
        ->toEqual('Lorem ipsum');
});

it('can write data to the stream', function () {
    $stream = new Stream();
    $stream->write('Lorem ipsum dolor sit amet');

    expect($stream)
        ->toEqual('Lorem ipsum dolor sit amet');
});

it('isWritable() returns false when a stream is unwriteable', function () {
    $stream = new Stream(fopen('php://temp', 'r'));

    expect($stream->isWritable())
        ->toBeFalse();

    $stream = new Stream(fopen('php://temp', 'r+'));

    expect($stream->isWritable())
        ->toBeTrue();

    $stream = new Stream(fopen('php://temp', 'w'));

    expect($stream->isWritable())
        ->toBeTrue();

    $stream = new Stream(fopen('php://temp', 'w+'));

    expect($stream->isWritable())
        ->toBeTrue();

    $stream = new Stream(fopen('php://temp', 'rw+'));

    expect($stream->isWritable())
        ->toBeTrue();
});

test('seek() throws an exception', function () {
    $resource = fopen('php://temp', 'r');
    $stream = new Stream($resource);
    $resource = null;
    $stream->seek(-100);
})->throws(\RuntimeException::class);

test('rewind() throws an exception', function () {
    $resource = fopen('php://temp', 'r+');
    $stream = new Stream($resource);
    $stream->close();
    $stream->rewind();
})->throws(\RuntimeException::class);

test('read() throws an exception', function () {
    $resource = fopen('php://temp', 'r+');
    $stream = new Stream($resource);
    $stream->read(-1000);
})->throws(\RuntimeException::class);

test('write() throws an exception', function () {
    $stream = new Stream(fopen('php://temp', 'r'));
    $stream->write(uniqid());
})->throws(\RuntimeException::class);

test('getContents() throws an exception', function () {
    $resource = fopen('php://temp', 'w');
    $stream = new Stream($resource);
    $stream->close();
    $stream->getContents();
})->throws(\RuntimeException::class);

test('getMetadata() returns an array', function () {
    $stream = new Stream();

    expect($stream->getMetadata())
        ->toBeArray();
});

test('getMetadata() returns null with a bad key', function () {
    $stream = new Stream();

    expect($stream->getMetadata(uniqid()))
        ->toBeNull();
});
