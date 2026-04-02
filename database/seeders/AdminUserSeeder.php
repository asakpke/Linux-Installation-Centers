<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Admin email and initial password.
     * Change the password after first login in production.
     */
    public const ADMIN_EMAIL = 'asakpke@msn.com';

    /** Initial admin password: L1cAdm1n#2025 */
    private const ADMIN_PASSWORD = 'L1cAdm1n#2025';

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => self::ADMIN_EMAIL],
            [
                'name' => 'Admin',
                'password' => Hash::make(self::ADMIN_PASSWORD),
                'role' => UserRole::ADMIN,
                'is_active' => true,
            ]
        );
    }
}
