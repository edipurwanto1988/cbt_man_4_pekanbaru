<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// Create a mock request
$request = new Request();
$request->merge([
    'email' => 'admin@edipurwanto.com',
    'password' => '12345678'
]);

echo "Testing admin login process...\n\n";

// Check if admin exists
$admin = Admin::where('email', 'admin@edipurwanto.com')->first();
if (!$admin) {
    echo "ERROR: Admin user not found in database!\n";
    exit;
}

echo "Admin user found:\n";
echo "  Email: " . $admin->email . "\n";
echo "  Name: " . $admin->name . "\n";
echo "  Role: " . $admin->role . "\n\n";

// Test password verification
if (Hash::check('12345678', $admin->password)) {
    echo "✓ Password verification successful\n";
} else {
    echo "✗ Password verification failed\n";
    exit;
}

// Test authentication attempt
$credentials = $request->only(['email', 'password']);
echo "\nTesting authentication with credentials:\n";
echo "  Email: " . $credentials['email'] . "\n";
echo "  Password: " . $credentials['password'] . "\n\n";

if (Auth::guard('admin')->attempt($credentials)) {
    echo "✓ Authentication successful!\n";
    $user = Auth::guard('admin')->user();
    echo "  Authenticated user: " . $user->name . " (" . $user->email . ")\n";
    echo "  User ID: " . $user->id . "\n";
    echo "  Role: " . $user->role . "\n";
    
    // Test logout
    Auth::guard('admin')->logout();
    echo "\n✓ Logout successful\n";
} else {
    echo "✗ Authentication failed!\n";
    
    // Debug: Check what's happening
    echo "\nDebug information:\n";
    echo "  Admin model: " . get_class($admin) . "\n";
    echo "  Password hash: " . $admin->password . "\n";
    echo "  Auth guard: admin\n";
}

echo "\nLogin test completed.\n";