@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="bg-white dark:bg-background-dark rounded-xl border border-primary/20 dark:border-primary/30">
    <div class="border-b border-primary/20 dark:border-primary/30">
        <nav class="flex space-x-8 px-6" aria-label="Tabs">
            @foreach($groupedSettings as $group => $settings)
                @if($loop->first && !request()->has('tab'))
                    <button class="py-4 px-1 border-b-2 text-sm font-medium border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"
                            data-tab="{{ str_replace(' ', '', $group) }}">
                        Pengaturan {{ $group }}
                    </button>
                @else
                    <button class="py-4 px-1 border-b-2 text-sm font-medium dark:text-gray-400 dark:hover:text-gray-300 border-primary text-primary"
                            data-tab="{{ str_replace(' ', '', $group) }}">
                        {{ $group }}
                    </button>
                @endif
            @endforeach
        </nav>
    </div>

    <div class="p-6">
        @foreach($groupedSettings as $group => $settings)
            <div id="{{ str_replace(' ', '', $group) }}-tab"
                 class="tab-content {{ ($loop->first && !request()->has('tab')) || (request()->has('tab') && str_replace(' ', '', request()->input('tab')) == str_replace(' ', '', $group)) ? '' : 'hidden' }}">
                <form method="POST" action="{{ route('admin.settings.updateMultiple') }}"
                      class="settings-form space-y-6"
                      data-group="{{ $group }}">
                    @csrf
                    @foreach($settings as $setting)
                        <div>
                            <label for="{{ str_replace(' ', '_', $setting->key) }}"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ str_replace('_', ' ', $setting->key) }}
                            </label>
                            
                            @if(str_contains(strtolower($setting->key), 'favicon') || str_contains(strtolower($setting->key), 'logo'))
                                <div class="file-upload-container">
                                    @if($setting->value && filter_var($setting->value, FILTER_VALIDATE_URL))
                                        <div class="mb-2">
                                            <img src="{{ $setting->value }}"
                                                 alt="{{ str_replace('_', ' ', $setting->key) }}"
                                                 class="h-16 w-auto inline-block"
                                                 onerror="this.style.display='none'; document.getElementById('no-preview-{{ str_replace(' ', '_', $setting->key) }}').style.display='block';">
                                            <span id="no-preview-{{ str_replace(' ', '_', $setting->key) }}" class="text-sm text-gray-500 hidden">Preview not available</span>
                                        </div>
                                    @endif
                                    
                                    <input type="file"
                                           id="file-{{ str_replace(' ', '_', $setting->key) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm dark:bg-background-dark dark:border-gray-600 dark:text-white"
                                           accept="image/*"
                                           data-setting-key="{{ $setting->key }}">
                                    
                                    <input type="hidden"
                                           id="{{ str_replace(' ', '_', $setting->key) }}"
                                           name="settings[{{ $setting->key }}]"
                                           value="{{ $setting->value }}">
                                    
                                    <div class="mt-2 flex space-x-2">
                                        <button type="button"
                                                class="upload-file-btn bg-primary text-white px-3 py-1 rounded text-sm hover:bg-primary/80 transition"
                                                data-setting-key="{{ $setting->key }}">
                                            <i class="fas fa-upload mr-1"></i> Upload
                                        </button>
                                        
                                        @if($setting->value)
                                            <button type="button"
                                                    class="remove-file-btn bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition"
                                                    data-setting-key="{{ $setting->key }}">
                                                <i class="fas fa-trash mr-1"></i> Remove
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <div class="upload-progress-{{ str_replace(' ', '_', $setting->key) }} hidden mt-2">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                            <div class="bg-primary h-2.5 rounded-full progress-bar" style="width: 0%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Uploading...</p>
                                    </div>
                                </div>
                            @elseif(str_contains(strtolower($setting->key), 'description') || str_contains(strtolower($setting->key), 'alamat') || str_contains(strtolower($setting->key), 'meta description') || str_contains(strtolower($setting->key), 'og description') || str_contains(strtolower($setting->key), 'twitter description') || str_contains(strtolower($setting->key), 'company description') || str_contains(strtolower($setting->key), 'login_description') || str_contains(strtolower($setting->key), 'instruksi'))
                                <textarea id="{{ str_replace(' ', '_', $setting->key) }}"
                                          name="settings[{{ $setting->key }}]"
                                          rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm dark:bg-background-dark dark:border-gray-600 dark:text-white">{{ $setting->value }}</textarea>
                            @elseif(str_contains(strtolower($setting->key), 'email'))
                                <input type="email"
                                       id="{{ str_replace(' ', '_', $setting->key) }}"
                                       name="settings[{{ $setting->key }}]"
                                       value="{{ $setting->value }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm dark:bg-background-dark dark:border-gray-600 dark:text-white">
                            @elseif(str_contains(strtolower($setting->key), 'telp') || str_contains(strtolower($setting->key), 'whanshapp'))
                                <input type="tel"
                                       id="{{ str_replace(' ', '_', $setting->key) }}"
                                       name="settings[{{ $setting->key }}]"
                                       value="{{ $setting->value }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm dark:bg-background-dark dark:border-gray-600 dark:text-white"
                                       @if(str_contains(strtolower($setting->key), 'whanshapp'))
                                           placeholder="Contoh: 628123456789"
                                       @endif>
                                @if(str_contains(strtolower($setting->key), 'whanshapp'))
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Nomor WhatsApp dengan kode negara (tanpa + atau 00)</p>
                                @endif
                            @else
                                <input type="text"
                                       id="{{ str_replace(' ', '_', $setting->key) }}"
                                       name="settings[{{ $setting->key }}]"
                                       value="{{ $setting->value }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm dark:bg-background-dark dark:border-gray-600 dark:text-white">
                            @endif
                        </div>
                    @endforeach

                    <div class="flex justify-end space-x-4">
                        <button type="button"
                                class="save-group-btn bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition"
                                data-group="{{ $group }}">
                            <i class="fas fa-save mr-2"></i> Simpan Pengaturan {{ $group }}
                        </button>
                        
                        <a href="{{ route('admin.settings.index') }}{{ request()->has('tab') ? '?tab=' . urlencode(request()->input('tab')) : '' }}"
                           class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get active tab from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const activeTabFromUrl = urlParams.get('tab');
    
    // Tab switching functionality
    const tabButtons = document.querySelectorAll('[data-tab]');
    const tabContents = document.querySelectorAll('.tab-content');
    
    // Set initial active tab based on URL parameter
    if (activeTabFromUrl) {
        const cleanActiveTab = activeTabFromUrl.replace(/\s+/g, '');
        
        // Hide all tab contents
        tabContents.forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active state from all buttons
        tabButtons.forEach(btn => {
            btn.classList.remove('border-primary', 'text-primary');
            btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            btn.classList.remove('dark:text-gray-400', 'dark:hover:text-gray-300');
        });
        
        // Show selected tab content
        const targetTabContent = document.getElementById(cleanActiveTab + '-tab');
        if (targetTabContent) {
            targetTabContent.classList.remove('hidden');
        }
        
        // Set active state for corresponding button
        tabButtons.forEach(btn => {
            if (btn.dataset.tab === cleanActiveTab) {
                btn.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                btn.classList.add('border-primary', 'text-primary');
                btn.classList.remove('dark:text-gray-400', 'dark:hover:text-gray-300');
            }
        });
    }
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            
            // Update URL without reloading the page
            const url = new URL(window.location);
            url.searchParams.set('tab', targetTab);
            window.history.pushState({}, '', url);
            
            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active state from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('border-primary', 'text-primary');
                btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                btn.classList.remove('dark:text-gray-400', 'dark:hover:text-gray-300');
            });
            
            // Show selected tab content
            document.getElementById(targetTab + '-tab').classList.remove('hidden');
            
            // Set active state for clicked button
            this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            this.classList.add('border-primary', 'text-primary');
            this.classList.remove('dark:text-gray-400', 'dark:hover:text-gray-300');
        });
    });
    
    // Handle save button for each tab
    document.querySelectorAll('.save-group-btn').forEach(button => {
        button.addEventListener('click', function() {
            const group = this.dataset.group;
            const form = document.querySelector(`.settings-form[data-group="${group}"]`);
            
            // Save CKEditor content before submitting if it exists
            const loginDescTextarea = document.querySelector('textarea[name="settings[Login_Description]"]');
            const instruksiPretestTextarea = document.querySelector('textarea[name="settings[Instruksi_Pretest]"]');
            const instruksiPosttestTextarea = document.querySelector('textarea[name="settings[Instruksi_Posttest]"]');
            
            if (loginDescTextarea && loginDescTextarea.ckeditorInstance) {
                loginDescTextarea.value = loginDescTextarea.ckeditorInstance.getData();
            }
            
            if (instruksiPretestTextarea && instruksiPretestTextarea.ckeditorInstance) {
                instruksiPretestTextarea.value = instruksiPretestTextarea.ckeditorInstance.getData();
            }
            
            if (instruksiPosttestTextarea && instruksiPosttestTextarea.ckeditorInstance) {
                instruksiPosttestTextarea.value = instruksiPosttestTextarea.ckeditorInstance.getData();
            }
            
            const formData = new FormData(form);
            
            // Add active tab to form data
            formData.append('active_tab', group);
            
            // Show loading state
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            this.disabled = true;
            
            fetch('{{ route("admin.settings.updateMultiple") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    showNotification('success', 'Settings untuk group ' + group + ' berhasil disimpan!');
                    
                    // Update URL to include active tab if it's not already there
                    if (data.active_tab) {
                        const url = new URL(window.location);
                        url.searchParams.set('tab', data.active_tab);
                        window.history.pushState({}, '', url);
                    }
                } else {
                    showNotification('error', 'Gagal menyimpan settings: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Terjadi kesalahan saat menyimpan settings');
            })
            .finally(() => {
                // Reset button state
                this.innerHTML = originalContent;
                this.disabled = false;
            });
        });
    });
    
    // Handle auto-save on input change (optional)
    document.querySelectorAll('input, textarea').forEach(input => {
        input.addEventListener('change', function() {
            // Save CKEditor content before submitting if it exists
            const loginDescTextarea = document.querySelector('textarea[name="settings[Login_Description]"]');
            const instruksiPretestTextarea = document.querySelector('textarea[name="settings[Instruksi_Pretest]"]');
            const instruksiPosttestTextarea = document.querySelector('textarea[name="settings[Instruksi_Posttest]"]');
            
            if (loginDescTextarea && loginDescTextarea.ckeditorInstance) {
                loginDescTextarea.value = loginDescTextarea.ckeditorInstance.getData();
            }
            
            if (instruksiPretestTextarea && instruksiPretestTextarea.ckeditorInstance) {
                instruksiPretestTextarea.value = instruksiPretestTextarea.ckeditorInstance.getData();
            }
            
            if (instruksiPosttestTextarea && instruksiPosttestTextarea.ckeditorInstance) {
                instruksiPosttestTextarea.value = instruksiPosttestTextarea.ckeditorInstance.getData();
            }
            
            const key = this.name.replace('settings[', '').replace(']', '');
            const value = this.value;
            
            // Find the active tab
            const activeTab = document.querySelector('.tab-content:not(.hidden)');
            const activeTabGroup = activeTab ? activeTab.querySelector('.settings-form').dataset.group : '';
            
            // Debounce the save request
            clearTimeout(this.saveTimeout);
            this.saveTimeout = setTimeout(() => {
                saveSingleSetting(key, value, activeTabGroup);
            }, 2000);
        });
    });
    
    function saveSingleSetting(key, value, activeTabGroup = '') {
        const formData = new FormData();
        formData.append('settings[' + key + ']', value);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('active_tab', activeTabGroup);
        
        fetch('{{ route("admin.settings.updateMultiple") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Setting ' + key + ' saved successfully');
                showNotification('success', 'Setting ' + key + ' tersimpan otomatis');
                
                // Update URL to include active tab if it's not already there
                if (data.active_tab) {
                    const url = new URL(window.location);
                    url.searchParams.set('tab', data.active_tab);
                    window.history.pushState({}, '', url);
                }
            }
        })
        .catch(error => {
            console.error('Error saving setting:', error);
        });
    }
    
    function showNotification(type, message) {
        // Remove existing notifications
        document.querySelectorAll('.notification').forEach(notification => notification.remove());
        
        const notification = document.createElement('div');
        notification.className = `notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white max-w-sm`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-3"></i>
                <div>
                    <p class="font-medium">${message}</p>
                </div>
                <button type="button" class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
    
    // Handle file upload functionality
    document.querySelectorAll('.upload-file-btn').forEach(button => {
        button.addEventListener('click', function() {
            const settingKey = this.dataset.settingKey;
            const fileInput = document.getElementById(`file-${settingKey.replace(/\s+/g, '_')}`);
            
            if (fileInput.files.length === 0) {
                showNotification('error', 'Please select a file first');
                return;
            }
            
            const file = fileInput.files[0];
            const formData = new FormData();
            formData.append('file', file);
            formData.append('setting_key', settingKey);
            formData.append('_token', '{{ csrf_token() }}');
            
            // Show progress
            const progressContainer = document.querySelector(`.upload-progress-${settingKey.replace(/\s+/g, '_')}`);
            const progressBar = progressContainer.querySelector('.progress-bar');
            progressContainer.classList.remove('hidden');
            
            // Disable button during upload
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Uploading...';
            
            fetch('{{ route("admin.settings.uploadFile") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                // Simulate progress
                let progress = 0;
                const interval = setInterval(() => {
                    progress += 10;
                    progressBar.style.width = progress + '%';
                    if (progress >= 90) {
                        clearInterval(interval);
                    }
                }, 100);
                
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update hidden input with file URL
                    document.getElementById(settingKey.replace(/\s+/g, '_')).value = data.file_url;
                    
                    // Update preview
                    const previewContainer = document.querySelector(`#file-${settingKey.replace(/\s+/g, '_')}`).previousElementSibling;
                    if (previewContainer && previewContainer.tagName === 'DIV') {
                        previewContainer.innerHTML = `
                            <img src="${data.file_url}"
                                 alt="${settingKey.replace(/_/g, ' ')}"
                                 class="h-16 w-auto inline-block"
                                 onerror="this.style.display='none'; document.getElementById('no-preview-${settingKey.replace(/\s+/g, '_')}').style.display='block';">
                            <span id="no-preview-${settingKey.replace(/\s+/g, '_')}" class="text-sm text-gray-500 hidden">Preview not available</span>
                        `;
                    }
                    
                    // Show remove button
                    const removeBtn = document.querySelector(`.remove-file-btn[data-setting-key="${settingKey}"]`);
                    if (removeBtn) {
                        removeBtn.classList.remove('hidden');
                    } else {
                        // Create remove button if it doesn't exist
                        const buttonContainer = this.parentElement;
                        const newRemoveBtn = document.createElement('button');
                        newRemoveBtn.type = 'button';
                        newRemoveBtn.className = 'remove-file-btn bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition';
                        newRemoveBtn.dataset.settingKey = settingKey;
                        newRemoveBtn.innerHTML = '<i class="fas fa-trash mr-1"></i> Remove';
                        buttonContainer.appendChild(newRemoveBtn);
                        
                        // Add event listener to new remove button
                        newRemoveBtn.addEventListener('click', handleRemoveFile);
                    }
                    
                    showNotification('success', 'File uploaded successfully!');
                } else {
                    showNotification('error', data.message || 'Error uploading file');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Error uploading file');
            })
            .finally(() => {
                // Reset button state
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-upload mr-1"></i> Upload';
                
                // Hide progress
                progressContainer.classList.add('hidden');
                progressBar.style.width = '0%';
                
                // Clear file input
                fileInput.value = '';
            });
        });
    });
    
    // Handle remove file functionality
    document.querySelectorAll('.remove-file-btn').forEach(button => {
        button.addEventListener('click', handleRemoveFile);
    });
    
    function handleRemoveFile() {
        const settingKey = this.dataset.settingKey;
        
        // Show loading state
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Removing...';
        
        const formData = new FormData();
        formData.append('setting_key', settingKey);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route("admin.settings.removeFile") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update hidden input with empty value
                document.getElementById(settingKey.replace(/\s+/g, '_')).value = '';
                
                // Remove preview
                const fileInput = document.getElementById(`file-${settingKey.replace(/\s+/g, '_')}`);
                const previewContainer = fileInput.previousElementSibling;
                if (previewContainer && previewContainer.tagName === 'DIV') {
                    previewContainer.innerHTML = '';
                }
                
                // Hide remove button
                this.classList.add('hidden');
                
                showNotification('success', 'File removed successfully!');
            } else {
                showNotification('error', data.message || 'Error removing file');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Error removing file');
        })
        .finally(() => {
            // Reset button state
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-trash mr-1"></i> Remove';
        });
    }
});

// Initialize CKEditor for Login_Description field
document.addEventListener('DOMContentLoaded', function() {
    // Check if ClassicEditor is loaded
    if (typeof ClassicEditor !== 'undefined') {
        // Find all textarea elements with name starting with "settings["
        const textareas = document.querySelectorAll('textarea[name^="settings["]');
        
        textareas.forEach(textarea => {
            // Initialize CKEditor for Login_Description, Instruksi_Pretest, and Instruksi_Posttest fields
            if (textarea.name === 'settings[Login_Description]' ||
                textarea.name === 'settings[Instruksi_Pretest]' ||
                textarea.name === 'settings[Instruksi_Posttest]') {
                ClassicEditor
                    .create(textarea, {
                        height: 200,
                        toolbar: [
                            'heading', '|',
                            'bold', 'italic', 'strikethrough', 'underline', '|',
                            'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'link', 'blockQuote', '|',
                            'undo', 'redo'
                        ]
                    })
                    .then(editor => {
                        // Store the editor instance so we can access it later
                        textarea.ckeditorInstance = editor;
                        
                        // Update the textarea when editor data changes
                        editor.model.document.on('change:data', () => {
                            textarea.value = editor.getData();
                        });
                    })
                    .catch(error => {
                        console.error('There was a problem initializing the CKEditor.', error);
                    });
            }
        });
    }
});
</script>

<style>
/* Fix for CKEditor bullet list alignment */
.ck-content ul {
    padding-left: 20px !important;
    margin-left: 0 !important;
}

.ck-content ol {
    padding-left: 20px !important;
    margin-left: 0 !important;
}

.ck-content li {
    margin-left: 0 !important;
    padding-left: 0 !important;
}

/* Additional CKEditor content styling fixes */
.ck-editor__editable {
    min-height: 200px;
}
</style>
@endsection