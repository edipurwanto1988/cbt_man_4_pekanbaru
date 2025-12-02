<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

// Check if admin with this email already exists
$admin = Admin::where('email', 'admin@edipurwanto.com')->first();

if (!$admin) {
    // Create new admin
    $admin = Admin::create([
        'name' => 'Edi Purwanto',
        'email' => 'admin@edipurwanto.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);
    echo "Admin user created successfully!\n";
} else {
    // Update existing admin password
    $admin->password = Hash::make('12345678');
    $admin->save();
    echo "Admin password updated successfully!\n";
}

echo "Admin details:\n";
echo "ID: " . $admin->id . "\n";
echo "Name: " . $admin->name . "\n";
echo "Email: " . $admin->email . "\n";
echo "Role: " . $admin->role . "\n";

// Verify password
if (Hash::check('12345678', $admin->password)) {
    echo "Password verification: SUCCESS\n";
} else {
    echo "Password verification: FAILED\n";
}