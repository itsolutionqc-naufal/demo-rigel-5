<?php

namespace Tests;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);

        // Safety net: tests must never run against the dev MySQL database.
        // If config is cached (bootstrap/cache/config.php), Laravel might ignore phpunit.xml DB_* overrides.
        if (config('database.default') !== 'sqlite') {
            throw new \RuntimeException(
                'Unsafe test DB configuration: expected sqlite, got "'.config('database.default')."\".\n"
                ."Run `php artisan config:clear` and retry. Do not run tests against MySQL dev/prod DB."
            );
        }
    }
}
