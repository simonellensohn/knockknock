<?php

namespace App\Providers;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\Concerns\TestDatabases;

class ParallelTestingServiceProvider extends ServiceProvider
{
    use TestDatabases;

    public function register(): void
    {
        // if ($this->app->runningInConsole()) {
        //     $this->app->singleton(\Illuminate\Testing\ParallelTesting::class, function ($app) {
        //         $parallelTesting = new ParallelTesting($app);
        //
        //         $parallelTesting->set
        //     });
        // }
    }

    public function boot(): void
    {
        ParallelTesting::setUpProcess(function ($token) {
            if (ParallelTesting::option('recreate_databases')) {
                $this->extraDatabases()->each(function ($connection) {
                    $database = config("database.connections.{$connection}.database");
                    Schema::dropDatabaseIfExists($this->testDatabase($database));
                });
            }
        });

        ParallelTesting::setUpTestCase(function ($token, $testCase) {
            $uses = array_flip(class_uses_recursive(get_class($testCase)));

            $databaseTraits = [
                DatabaseMigrations::class,
                DatabaseTransactions::class,
                RefreshDatabase::class,
                LazilyRefreshDatabase::class,
            ];

            if (Arr::hasAny($uses, $databaseTraits) && ! ParallelTesting::option('without_databases')) {
                $this->extraDatabases()->each(function ($connection) {
                    $database = config("database.connections.{$connection}.database");

                    [$testDatabase, $created] = $this->ensureTestDatabaseExists($database);

                    $this->switchToDatabase($testDatabase, $connection);

                    if ($created) {
                        $this->usingDatabase(
                            $testDatabase,
                            fn () => $this->setUpDatabase($connection, $testDatabase),
                            $connection
                        );
                    }
                });
            }
        });

        // Executed when a test database is created...
        ParallelTesting::setUpTestDatabase(function ($database, $token) {
            // dump($database, $token);
        });

        ParallelTesting::tearDownTestCase(function ($token, $testCase) {
            // ...
        });

        ParallelTesting::tearDownProcess(function ($token) {
            // ...
        });
    }

    private function switchToDatabase(string $database, string $connection = 'default'): void
    {
        DB::purge($connection);

        $url = config("database.connections.{$connection}.url");

        // dump($database);

        if ($url) {
            config()->set(
                "database.connections.{$connection}.url",
                preg_replace('/^(.*)(\/[\w-]*)(\??.*)$/', "$1/{$database}$3", $url),
            );
        } else {
            config()->set(
                "database.connections.{$connection}.database",
                $database,
            );
        }
    }

    private function extraDatabases(): Collection
    {
        return collect(['external']);
    }

    protected function usingDatabase($database, $callable, $connection = 'mysql')
    {
        $original = DB::connection($connection)->getConfig('database');

        try {
            $this->switchToDatabase($database, $connection);
            $callable();
        } finally {
            $this->switchToDatabase($original, $connection);
        }
    }

    protected function ensureTestDatabaseExists($database, $connection = 'mysql')
    {
        $testDatabase = $this->testDatabase($database);

        try {
            $this->usingDatabase(
                $testDatabase,
                fn () => Schema::hasTable('dummy'),
                $connection
            );
        } catch (QueryException $e) {
            $this->usingDatabase($database, function () use ($testDatabase) {
                Schema::dropDatabaseIfExists($testDatabase);
                Schema::createDatabase($testDatabase);
            }, $connection);

            return [$testDatabase, true];
        }

        return [$testDatabase, false];
    }

    private function setUpDatabase($connection, $database)
    {
        DB::connection($connection)
            ->getSchemaState()
            ->load(base_path("tests/Fixtures/Databases/{$connection}.dump"));
    }
}
