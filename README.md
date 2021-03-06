# Laravel Connection Pool

[![Build Status](https://travis-ci.com/mink/laravel-connection-pool.svg?branch=master)](https://travis-ci.com/mink/laravel-connection-pool)

Laravel Connection Pool allows you to take advantage of Laravel's [query builder](https://laravel.com/docs/8.x/queries) and [Eloquent ORM](https://laravel.com/docs/8.x/eloquent) in an asynchronous environment. Perform concurrent database operations through a familiar fluent interface without having to worry about locking contention, let alone what connection you are using.

**Note:** This package is a work in progress and is unsafe for use in production.

### Requirements
- PHP 7.4+ (CLI only)
- [Swoole](https://github.com/swoole/swoole-src) PHP extension

### Installation
```
composer require x/laravel-connection-pool
```

### Usage

```php
// concurrent operations
go(fn () => Server::where('terminated_at', '<=', now()->subMinutes(5))->delete());

go(function () {
    Session::where('active', true)->get()->each(function ($session) {
        //
    });
});

Swoole\Event::wait();
```

```php
// run 100 SLEEP(1) queries at once, takes ~1s
for ($i = 0; $i < 100; $i++) {
    go(fn () => DB::statement('SELECT SLEEP(1)'));
}

Swoole\Event::wait();
```
