<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use App\Models\Perusahaan;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->reset();

        $admin = User::create([
            'name' => 'Aang Supriatna',
            'email' => 'admin@email.com',
            'password' => Hash::make('password'),
        ]);

        $user = User::create([
            'name' => 'User',
            'email' => 'user@email.com',
            'password' => Hash::make('password'),
        ]);

        $perusahaan = Perusahaan::create([
            'name' => 'PT. Miskat Alam Pro',
            'slug' => 'pt-miskat-alam-pro',
        ]);

        $perusahaan->users()->attach($admin->id);
        $perusahaan->users()->attach($user->id);
    }

    public function reset()
    {
        Schema::disableForeignKeyConstraints();

        User::truncate();
        Perusahaan::truncate();

        Schema::disableForeignKeyConstraints();
    }
}
