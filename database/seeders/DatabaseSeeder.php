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
        $account = Account::create(['name' => 'MBORASYSTEM ADMIN']);

        User::factory()->create([
            'account_id' => $account->id,
            'first_name' => 'António',
            'last_name' => 'Teca',
            'email' => 'antonioteca@hotmail.com',
            'owner' => true,
        ]);

        User::factory()->count(5)->create([
            'account_id' => $account->id
        ]);

        $organizations = Organization::factory()->count(20)->create([
            'account_id' => $account->id
        ]);

        Contact::factory()->count(20)->create([
            'account_id' => $account->id
        ])
            ->each(function (Contact  $contact) use ($organizations) {
                $contact->update(['organization_id' => $organizations->random()->id]);
            });
    }
}
