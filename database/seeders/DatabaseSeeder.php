<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $account = Account::firstOrCreate(['name' => 'MBORASYSTEM ADMIN']);

        $primaryEmail = env('SEED_PRIMARY_EMAIL', 'owner@mbora.local');
        $primaryPassword = env('SEED_PRIMARY_PASSWORD', 'ChangeThis#Mbora2026!');
        $backupEmail = env('SEED_BACKUP_EMAIL', 'admin.backup@mbora.local');
        $backupPassword = env('SEED_BACKUP_PASSWORD', 'ChangeThisBackup#Mbora2026!');

        User::updateOrCreate(
            [
                'account_id' => $account->id,
                'first_name' => 'António',
                'last_name' => 'Teca',
            ],
            [
                'email' => $primaryEmail,
                'password' => $primaryPassword,
                'owner' => true,
            ]
        );

        User::updateOrCreate(
            [
                'account_id' => $account->id,
                'first_name' => 'Admin',
                'last_name' => 'Backup',
            ],
            [
                'email' => $backupEmail,
                'password' => $backupPassword,
                'owner' => true,
            ]
        );

        // User::factory()->count(5)->create([
        //     'account_id' => $account->id
        // ]);

        // $organizations = Organization::factory()->count(20)->create([
        //     'account_id' => $account->id
        // ]);

        // Contact::factory()->count(20)->create([
        //     'account_id' => $account->id
        // ])
        //     ->each(function (Contact  $contact) use ($organizations) {
        //         $contact->update(['organization_id' => $organizations->random()->id]);
        //     });
    }
}
