<?php

namespace SamuelNitsche\AuthLog\Tests;

use CreateAuthLogTable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use SamuelNitsche\AuthLog\AuthLogServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app): array
    {
        return [
            AuthLogServiceProvider::class,
        ];
    }

    protected function setUpDatabase()
    {
        $this->createAuthLogTable();

        $this->createUserTable();
    }

    protected function createAuthLogTable()
    {
        include_once __DIR__.'/../database/migrations/2017_09_01_000000_create_auth_log_table.php';

        (new CreateAuthLogTable())->up();
    }

    protected function createUserTable()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }
}
