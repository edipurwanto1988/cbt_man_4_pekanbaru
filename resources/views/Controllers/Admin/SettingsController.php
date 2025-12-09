<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get();
        $groupedSettings = Setting::getGrouped();
        
        return view('admin.settings.index', compact('settings', 'groupedSettings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Setting::distinct()->pluck('group');
        return view('admin.settings.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255|unique:settings,key',
            'value' => 'required|string',
            'group' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Setting::create($request->all());

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $setting = Setting::findOrFail($id);
        return view('admin.settings.show', compact('setting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $setting = Setting::findOrFail($id);
        $groups = Setting::distinct()->pluck('group');
        return view('admin.settings.edit', compact('setting', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255|unique:settings,key,' . $id,
            'value' => 'required|string',
            'group' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $setting = Setting::findOrFail($id);
        $setting->update($request->all());

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting berhasil dihapus.');
    }

    /**
     * Update multiple settings at once.
     */
    public function updateMultiple(Request $request)
    {
        try {
            $settings = $request->input('settings', []);
            $activeTab = $request->input('active_tab', '');
            
            // Log the incoming settings data for debugging
            Log::info('Settings update request data:', [
                'all_request_data' => $request->all(),
                'settings_array' => $settings,
                'active_tab' => $activeTab,
                'alamat_value' => isset($settings['Alamat']) ? $settings['Alamat'] : 'NOT_SET',
                'alamat_is_null' => isset($settings['Alamat']) ? is_null($settings['Alamat']) : 'NOT_SET',
                'alamat_is_empty' => isset($settings['Alamat']) ? empty($settings['Alamat']) : 'NOT_SET'
            ]);
            
            foreach ($settings as $key => $value) {
                // Convert null values to empty strings to prevent database constraint violation
                $processedValue = $value === null ? '' : $value;
                
                // Log each update attempt
                Log::info("Updating setting: key={$key}, original_value=" . var_export($value, true) . ", processed_value=" . var_export($processedValue, true));
                
                Setting::where('key', $key)->update(['value' => $processedValue]);
            }

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Settings berhasil diperbarui.',
                    'active_tab' => $activeTab
                ]);
            }

            // If not AJAX, redirect with active tab parameter
            $redirectUrl = route('admin.settings.index');
            if ($activeTab) {
                $redirectUrl .= '?tab=' . urlencode($activeTab);
            }
            
            return redirect($redirectUrl)
                ->with('success', 'Settings berhasil diperbarui.');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Settings update error: ' . $e->getMessage());
            Log::error('Settings update trace: ' . $e->getTraceAsString());
            
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan settings: ' . $e->getMessage());
        }
    }

    /**
     * Upload file for settings (favicon, logo, etc.)
     */
    public function uploadFile(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:2048', // Max 2MB
                'setting_key' => 'required|string|max:255'
            ]);

            $file = $request->file('file');
            $settingKey = $request->input('setting_key');
            
            // Determine file type and allowed extensions
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'ico', 'svg'];
            $extension = $file->getClientOriginalExtension();
            
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File type not allowed. Allowed types: ' . implode(', ', $allowedExtensions)
                ], 422);
            }
            
            // Generate unique filename
            $filename = $settingKey . '_' . time() . '.' . $extension;
            
            // Store file in public/uploads/settings directory
            $path = $file->storeAs('uploads/settings', $filename, 'public');
            
            // Get the full URL for the file
            $fileUrl = asset('storage/' . $path);
            
            // Update the setting value with the file URL
            $setting = Setting::where('key', $settingKey)->first();
            if ($setting) {
                // If there was an old file, delete it
                if ($setting->value && filter_var($setting->value, FILTER_VALIDATE_URL)) {
                    $oldFilePath = public_path(parse_url($setting->value, PHP_URL_PATH));
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
                
                $setting->value = $fileUrl;
                $setting->save();
            } else {
                Setting::create([
                    'key' => $settingKey,
                    'value' => $fileUrl,
                    'group' => 'Umum', // Default group
                    'type' => 'file'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'file_url' => $fileUrl,
                'setting_key' => $settingKey
            ]);
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error uploading file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove file for settings (favicon, logo, etc.)
     */
    public function removeFile(Request $request)
    {
        try {
            $request->validate([
                'setting_key' => 'required|string|max:255'
            ]);

            $settingKey = $request->input('setting_key');
            
            // Find the setting
            $setting = Setting::where('key', $settingKey)->first();
            
            if (!$setting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Setting not found'
                ], 404);
            }
            
            // If there was an old file, delete it
            if ($setting->value && filter_var($setting->value, FILTER_VALIDATE_URL)) {
                $oldFilePath = public_path(parse_url($setting->value, PHP_URL_PATH));
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            
            // Clear the setting value
            $setting->value = '';
            $setting->save();
            
            return response()->json([
                'success' => true,
                'message' => 'File removed successfully',
                'setting_key' => $settingKey
            ]);
        } catch (\Exception $e) {
            Log::error('File removal error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error removing file: ' . $e->getMessage()
            ], 500);
        }
    }
}