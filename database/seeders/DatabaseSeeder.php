<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Varsa hata verme, yoksa oluştur
        $p1 = Permission::findOrCreate('görev ekle');
        $p2 = Permission::findOrCreate('görev sil');

        $adminRole = Role::findOrCreate('Admin');
        $stajyerRole = Role::findOrCreate('Stajyer');

        $adminRole->givePermissionTo(Permission::all());

        // Kullanıcı varsa Admin rolünü ata
        $user = User::first();
        if ($user) {
            $user->assignRole($adminRole);
        }
    }
}