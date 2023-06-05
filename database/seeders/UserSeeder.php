<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

      $this->command->newLine();
      $this->command->info('Dummy clients API keys below, use these as a Bearer token when accessing the API.');

        $luftHansa = \App\Models\User::factory()->create([
            'name' => 'Lufthansa',
            'email' => 'lufthansa@fr24.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'email_verified_at' => Carbon::now()->toDateTimeString()
        ]);
        $luftHansaToken = $luftHansa->createToken('api_token');

        $easyJet = \App\Models\User::factory()->create([
            'name' => 'EasyJet',
            'email' => 'easyjet@fr24.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'email_verified_at' => Carbon::now()->toDateTimeString()
        ]);
        $easyJetToken = $easyJet->createToken('api_token');

        $boeing = \App\Models\User::factory()->create([
            'name' => 'Boeing',
            'email' => 'boeing@fr24.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'email_verified_at' => Carbon::now()->toDateTimeString()
        ]);
        $boeingToken = $boeing->createToken('api_token');

        $this->command->table(
            ['End user', 'Token'],
            [
                ['Boeing', substr($boeingToken->plainTextToken, 2)],
                ['EasyJet', substr($easyJetToken->plainTextToken, 2)],
                ['LuftHansa', substr($luftHansaToken->plainTextToken, 2)]
            ]
        );
        $this->command->newLine();
    }
}
