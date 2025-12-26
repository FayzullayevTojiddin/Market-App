<?php
use App\Models\User;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => "Super Admin",
            'email' => 'super@labor.uz',
            'password' => 'As123456',
            'role' => 'super'
        ]);
    }
}