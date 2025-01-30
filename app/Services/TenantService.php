<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;

class TenantService
{
    private ?Tenant $tenant;
    private $domain;
    private $database;

    // Switche To Tenant
    public function switchToTenant(?Tenant $tenant)
    {
        if (!$tenant) {
            throw ValidationException::withMessages(['Tenant not found']);
        }
        $this->tenant = $tenant;
        $this->domain = $tenant->domain;
        $this->database = $tenant->database;
        DB::purge('system');
        Config::set('database.connections.tenant.database', $tenant->database);
        DB::connection('tenant')->reconnect();
        DB::setDefaultConnection('tenant');
    }

    // Switch to System
    public function switchToSystem()
    {
        DB::purge('system');
        DB::purge('tenant');
        DB::connection('system')->reconnect();
        DB::setDefaultConnection('system');
    }

    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function getTenantUser(): ?User
    {
        return $this->tenant->user;
    }

    public function getTenantDomain(): string
    {
        return $this->domain;
    }

    public function getTenantDatabase(): string
    {
        return $this->database;
    }

    // Create Tenant Data and Migrate tenant tables
    public function createTenant($userId, $name, $domain, $database)
    {
        $db_name = "tenant-{$database}";
        try {
            DB::beginTransaction();
            $tenant = Tenant::createOrFirst([
                'user_id' => $userId,
                'name' => $name,
                'domain' => $domain,
                'database' => $db_name,
            ]);

            // Create Database If not already created
            DB::statement("CREATE DATABASE `{$db_name}`");

            $this->switchToTenant($tenant);

            Artisan::call('migrate', ['--path' => '/database/migrations/tenant', '--database' => 'tenant']);

            DB::commit();
            return $tenant;
        } catch (QueryException $e) {
            DB::rollback();
            return false;
        }
    }
}