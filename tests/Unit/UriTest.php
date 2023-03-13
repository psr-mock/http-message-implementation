<?php

use PsrMock\Psr7\Uri;

it('throws an exception when an invalid URI is used', function () {
    $uri = new Uri('this-is-not-a-valid-url');
})->throws(\InvalidArgumentException::class);

it('can be constructed with a URI string', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    expect($uri->getScheme())->toBe('https');
    expect($uri->getHost())->toBe('example.com');
    expect($uri->getPath())->toBe('/path');
    expect($uri->getQuery())->toBe('foo=bar');
    expect($uri->getFragment())->toBe('');
});

it('can set the scheme', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withScheme('http');
    expect($newUri->getScheme())->toBe('http');
});

it('throws an exception when the scheme is not a string', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withScheme(123);
})->throws(\InvalidArgumentException::class);

it('can set the user info', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withUserInfo('user');
    expect($newUri->getUserInfo())->toBe('user');
});

it('throws an exception when the user password is not a string', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withUserInfo('user', 123);
})->throws(\InvalidArgumentException::class);

it('can set the user info with a password', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withUserInfo('user', 'password');
    expect($newUri->getUserInfo())->toBe('user:password');
});

it('returns an empty string when the user info is empty', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withUserInfo('');
    expect($newUri->getUserInfo())->toBe('');
});

it('throws an exception when the user info is null', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withUserInfo(null);
    expect($newUri->getUserInfo())->toBe('');
})->throws(\InvalidArgumentException::class);

it('throws an exception when the user info is not a string', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withUserInfo(123);
})->throws(\InvalidArgumentException::class);

it('can set the host', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withHost('example.org');
    expect($newUri->getHost())->toBe('example.org');
});

it('throws an exception when the host is not a string', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withHost(123);
})->throws(\InvalidArgumentException::class);

it('can set the port', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withPort(8080);
    expect($newUri->getPort())->toBe(8080);
});

it('throws an exception when the port is not an integer', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withPort('8080');
})->throws(\InvalidArgumentException::class);

it('throws an exception when the port is not a valid port', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withPort(123456);
})->throws(\InvalidArgumentException::class);

it('can set the path', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withPath('/new-path');
    expect($newUri->getPath())->toBe('/new-path');
});

it('throws an exception when the path is not a string', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withPath(123);
})->throws(\InvalidArgumentException::class);

it('can set the query', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withQuery('baz=qux');
    expect($newUri->getQuery())->toBe('baz=qux');
});

it('throws an exception when the query is not a string', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withQuery(123);
})->throws(\InvalidArgumentException::class);

it('can set the fragment', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withFragment('section');
    expect($newUri->getFragment())->toBe('section');
});

it('throws an exception when the fragment is not a string', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    $newUri = $uri->withFragment(123);
})->throws(\InvalidArgumentException::class);

it('returns authority with user info', function () {
    $uri = new Uri('https://user:password@testing.com:8080/path?foo=bar');
    expect($uri->getAuthority())->toBe('user:password@testing.com:8080');
});

it('can be converted to a string', function () {
    $uri = new Uri('https://example.com/path?foo=bar');
    expect((string) $uri)->toBe('https://example.com/path?foo=bar');
});

it('can be converted to a string with a port', function () {
    $uri = new Uri('https://example.com:8080/path?foo=bar');
    expect((string) $uri)->toBe('https://example.com:8080/path?foo=bar');
});

it('can be converted to a string with a fragment', function () {
    $uri = new Uri('https://example.com/path?foo=bar#section');
    expect((string) $uri)->toBe('https://example.com/path?foo=bar#section');
});

it('can be converted to a string with a user info', function () {
    $uri = new Uri('https://user:password@testing.com/path?foo=bar');
    expect((string) $uri)->toBe('https://user:password@testing.com/path?foo=bar');
});

it('can be converted to a string with a user info and a port', function () {
    $uri = new Uri('https://user:password@testing.com:8080/path?foo=bar');
    expect((string) $uri)->toBe('https://user:password@testing.com:8080/path?foo=bar');
});
