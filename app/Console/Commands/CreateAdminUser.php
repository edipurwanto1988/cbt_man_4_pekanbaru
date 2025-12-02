<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {email=admin@edipurwanto.com} {password=12345678} {name=Edi Purwanto}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->argument('name');

        // Check if admin already exists
        $existingAdmin = Admin::where('email', $email)->first();
        
        if ($existingAdmin) {
            $this->info("Admin with email {$email} already exists. Updating password...");
            $existingAdmin->password = Hash::make($password);
            $existingAdmin->save();
            $this->info("Admin password updated successfully!");
        } else {
            // Create new admin
            $admin = Admin::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
            ]);
            
            $this->info("Admin user created successfully!");
            $this->info("Email: {$admin->email}");
            $this->info("Name: {$admin->name}");
            $this->info("Password: {$password}");
        }

        return Command::SUCCESS;
    }
}