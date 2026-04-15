<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

// Check if admin user exists
$admin = Admin::where('email', 'admin@edipurwanto.com')->first();

if ($admin) {
    echo "Admin user already exists:\n";
    echo "Name: " . $admin->name . "\n";
    echo "Email: " . $admin->email . "\n";
    echo "Role: " . $admin->role . "\n";
} else {
    echo "Admin user does not exist. Creating now...\n";
    
    // Create the admin user
    $newAdmin = Admin::create([
        'name' => 'Edi Purwanto',
        'email' => 'admin@edipurwanto.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);
    
    echo "Admin user created successfully:\n";
    echo "Name: " . $newAdmin->name . "\n";
    echo "Email: " . $newAdmin->email . "\n";
    echo "Role: " . $newAdmin->role . "\n";
}