<?php

declare(strict_types=1);

namespace X\LaravelConnectionPool;

use Closure;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

class MySqlPoolServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerConnectionResolver();
    }

    /**
     * Register the connection pool resolver.
     *
     * @return void
     */
    protected function registerConnectionResolver(): void
    {
        // todo - PDO or closure
        Connection::resolverFor('mysql', function (
            Closure $connection,
            string  $database,
            string  $prefix,
            array   $config
        ) {
            /** @var DatabaseManager $manager */
            $manager = $this->app->get('db');
            return (new MySqlConnection(
                $connection,
                $database,
                $prefix,
                $config,
            ))->setDatabaseManager($manager);
        });
    }
}
