<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing settings
        DB::table('settings')->delete();
        
        $settings = [
            // Umum group
            ['key' => 'Nama_Institusi', 'value' => '', 'group' => 'Umum', 'type' => 'text'],
            ['key' => 'Alamat', 'value' => '', 'group' => 'Umum', 'type' => 'text'],
            ['key' => 'Email', 'value' => '', 'group' => 'Umum', 'type' => 'text'],
            ['key' => 'Telp', 'value' => '', 'group' => 'Umum', 'type' => 'text'],
            ['key' => 'Whatshapp', 'value' => '', 'group' => 'Umum', 'type' => 'text'],
            ['key' => 'Favicon', 'value' => '', 'group' => 'Umum', 'type' => 'file'],
            ['key' => 'Logo', 'value' => '', 'group' => 'Umum', 'type' => 'file'],
            
            // Login group
            ['key' => 'Title', 'value' => '', 'group' => 'Login', 'type' => 'text'],
            ['key' => 'Sort_Description', 'value' => '', 'group' => 'Login', 'type' => 'text'],
            ['key' => 'Login_Description', 'value' => '', 'group' => 'Login', 'type' => 'textarea'],
            ['key' => 'Footer', 'value' => '', 'group' => 'Login', 'type' => 'text'],
            
            // SMO & SEO group
            ['key' => 'Facebook', 'value' => '', 'group' => 'SMO & SEO', 'type' => 'text'],
            ['key' => 'Instagram', 'value' => '', 'group' => 'SMO & SEO', 'type' => 'text'],
            ['key' => 'Tiktok', 'value' => '', 'group' => 'SMO & SEO', 'type' => 'text'],
            ['key' => 'Twitter', 'value' => '', 'group' => 'SMO & SEO', 'type' => 'text'],
            ['key' => 'Youtube', 'value' => '', 'group' => 'SMO & SEO', 'type' => 'text'],
            ['key' => 'Google_Site_Verication', 'value' => '', 'group' => 'SMO & SEO', 'type' => 'text'],
            ['key' => 'Image', 'value' => '', 'group' => 'SMO & SEO', 'type' => 'text'],
            ['key' => 'SEO_Description', 'value' => '', 'group' => 'SMO & SEO', 'type' => 'text'],
            ['key' => 'Keyword', 'value' => '', 'group' => 'SMO & SEO', 'type' => 'text'],
            
            // CBT group
            ['key' => 'Tampilkan_Nilai_Setelah_Selesai', 'value' => '', 'group' => 'CBT', 'type' => 'text'],
            ['key' => 'Instruksi_Pretest', 'value' => '', 'group' => 'CBT', 'type' => 'textarea'],
            ['key' => 'Instruksi_Posttest', 'value' => '', 'group' => 'CBT', 'type' => 'textarea'],
        ];

        DB::table('settings')->insert($settings);
    }
}