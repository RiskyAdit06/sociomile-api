<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $tenant1 = Tenant::create(['name'=>'Tenant A']);
        $tenant2 = Tenant::create(['name'=>'Tenant B']);

        User::create([
            'name'=>'Admin Tenant A',
            'email'=>'adminA@example.com',
            'password'=>Hash::make('Password123!'),
            'role'=>'admin',
            'tenant_id'=>$tenant1->id
        ]);

        User::create([
            'name'=>'Agent Tenant A',
            'email'=>'agentA@example.com',
            'password'=>Hash::make('Password123!'),
            'role'=>'agent',
            'tenant_id'=>$tenant1->id
        ]);

        User::create([
            'name'=>'Admin Tenant B',
            'email'=>'adminB@example.com',
            'password'=>Hash::make('Password123!'),
            'role'=>'admin',
            'tenant_id'=>$tenant2->id
        ]);

        User::create([
            'name'=>'Agent Tenant B',
            'email'=>'agentB@example.com',
            'password'=>Hash::make('Password123!'),
            'role'=>'agent',
            'tenant_id'=>$tenant2->id
        ]);
    }
}

