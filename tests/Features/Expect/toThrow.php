<?php

use PHPUnit\Framework\ExpectationFailedException;

test('passes', function () {
    expect(function () { throw new RuntimeException(); })->toThrow(RuntimeException::class);
    expect(function () { throw new RuntimeException(); })->toThrow(Exception::class);
    expect(function () { throw new RuntimeException(); })->toThrow(function (RuntimeException $e) {});
    expect(function () { throw new RuntimeException('actual message'); })->toThrow(function (Exception $e) {
        expect($e->getMessage())->toBe('actual message');
    });
    expect(function () {})->not->toThrow(Exception::class);
    expect(function () { throw new RuntimeException('actual message'); })->toThrow('actual message');
    expect(function () { throw new Exception(); })->not->toThrow(RuntimeException::class);
    expect(function () { throw new RuntimeException('actual message'); })->toThrow(RuntimeException::class, 'actual message');
    expect(function () { throw new RuntimeException('actual message'); })->toThrow(function (RuntimeException $e) {}, 'actual message');
});

test('failures 1', function () {
    expect(function () {})->toThrow(RuntimeException::class);
})->throws(ExpectationFailedException::class, 'Exception "' . RuntimeException::class . '" not thrown.');

test('failures 2', function () {
    expect(function () {})->toThrow(function (RuntimeException $e) {});
})->throws(ExpectationFailedException::class, 'Exception "' . RuntimeException::class . '" not thrown.');

test('failures 3', function () {
    expect(function () { throw new Exception(); })->toThrow(function (RuntimeException $e) {});
})->throws(ExpectationFailedException::class, 'Failed asserting that Exception Object');

test('failures 4', function () {
    expect(function () { throw new Exception('actual message'); })
        ->toThrow(function (Exception $e) {
            expect($e->getMessage())->toBe('expected message');
        });
})->throws(ExpectationFailedException::class, 'Failed asserting that two strings are identical');

test('failures 5', function () {
    expect(function () { throw new Exception('actual message'); })->toThrow('expected message');
})->throws(ExpectationFailedException::class, 'Failed asserting that \'actual message\' contains "expected message".');

test('failures 6', function () {
    expect(function () {})->toThrow('actual message');
})->throws(ExpectationFailedException::class, 'Exception with message "actual message" not thrown');

test('failures 7', function () {
    expect(function () { throw new RuntimeException('actual message'); })->toThrow(RuntimeException::class, 'expected message');
})->throws(ExpectationFailedException::class);

test('not failures', function () {
    expect(function () { throw new RuntimeException(); })->not->toThrow(RuntimeException::class);
})->throws(ExpectationFailedException::class);

test('closure missing parameter', function () {
    expect(function () {})->toThrow(function () {});
})->throws(InvalidArgumentException::class, 'The given closure must have a single parameter type-hinted as the class string.');

test('closure missing type-hint', function () {
    expect(function () {})->toThrow(function ($e) {});
})->throws(InvalidArgumentException::class, 'The given closure\'s parameter must be type-hinted as the class string.');
