@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="bg-white dark:bg-background-dark rounded-xl border border-primary/20 dark:border-primary/30">
    <div class="border-b border-primary/20 dark:border-primary/30">
        <nav class="flex space-x-8 px-6" aria-label="Tabs">
            @foreach($groupedSettings as $group => $settings)
                <button class="py-4 px-1 border-b-2 text-sm font-medium {{ $loop->first && !request()->has('tab') ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                        data-tab="{{ str_replace(' ', '', $group) }}">
                    {{ $group }}
                </button>
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
                            
                            @if($setting->type === 'file')
                                {{-- File Upload Field --}}
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
                            
                            @elseif($setting->type === 'textarea')
                                {{-- Textarea Field --}}
                                <textarea id="{{ str_replace(' ', '_', $setting->key) }}"
                                          name="settings[{{ $setting->key }}]"
                                          rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm dark:bg-background-dark dark:border-gray-600 dark:text-white"
                                          data-setting-key="{{ $setting->key }}">{{ $setting->value }}</textarea>
                            
                            @else
                                {{-- Text Input Field (default) --}}
                                <input type="text"
                                       id="{{ str_replace(' ', '_', $setting->key) }}"
                                       name="settings[{{ $setting->key }}]"
                                       value="{{ $setting->value }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm dark:bg-background-dark dark:border-gray-600 dark:text-white">
                            @endif
                            
                            @if(str_contains(strtolower($setting->key), 'whatsapp'))
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Nomor WhatsApp dengan kode negara (tanpa + atau 00)</p>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = '{{ csrf_token() }}';
    
    // Get active tab from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const activeTabFromUrl = urlParams.get('tab');
    
    // Tab switching functionality
    const tabButtons = document.querySelectorAll('[data-tab]');
    const tabContents = document.querySelectorAll('.tab-content');
    
    // Set initial active tab based on URL parameter
    if (activeTabFromUrl) {
        const cleanActiveTab = activeTabFromUrl.replace(/\s+/g, '');
        
        tabContents.forEach(content => content.classList.add('hidden'));
        
        tabButtons.forEach(btn => {
            btn.classList.remove('border-primary', 'text-primary');
            btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        });
        
        const targetTabContent = document.getElementById(cleanActiveTab + '-tab');
        if (targetTabContent) {
            targetTabContent.classList.remove('hidden');
        }
        
        tabButtons.forEach(btn => {
            if (btn.dataset.tab === cleanActiveTab) {
                btn.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                btn.classList.add('border-primary', 'text-primary');
            }
        });
    }
    
    // Tab button click handlers
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            
            const url = new URL(window.location);
            url.searchParams.set('tab', targetTab);
            window.history.pushState({}, '', url);
            
            tabContents.forEach(content => content.classList.add('hidden'));
            
            tabButtons.forEach(btn => {
                btn.classList.remove('border-primary', 'text-primary');
                btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            
            document.getElementById(targetTab + '-tab').classList.remove('hidden');
            
            this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            this.classList.add('border-primary', 'text-primary');
        });
    });
    
    // Save button handlers
    document.querySelectorAll('.save-group-btn').forEach(button => {
        button.addEventListener('click', function() {
            const group = this.dataset.group;
            const form = document.querySelector(`.settings-form[data-group="${group}"]`);
            
            // Update CKEditor content before submitting
            updateAllCKEditorContent();
            
            const formData = new FormData(form);
            formData.append('active_tab', group);
            
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            this.disabled = true;
            
            fetch('{{ route("admin.settings.updateMultiple") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', 'Settings untuk group ' + group + ' berhasil disimpan!');
                    
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
                this.innerHTML = originalContent;
                this.disabled = false;
            });
        });
    });
    
    // Auto-save on input change
    document.querySelectorAll('input:not([type="file"]), textarea').forEach(input => {
        input.addEventListener('change', function() {
            updateAllCKEditorContent();
            
            const key = this.name.replace('settings[', '').replace(']', '');
            const value = this.value;
            
            const activeTab = document.querySelector('.tab-content:not(.hidden)');
            const activeTabGroup = activeTab ? activeTab.querySelector('.settings-form').dataset.group : '';
            
            clearTimeout(this.saveTimeout);
            this.saveTimeout = setTimeout(() => {
                saveSingleSetting(key, value, activeTabGroup);
            }, 2000);
        });
    });
    
    function updateAllCKEditorContent() {
        document.querySelectorAll('textarea[data-setting-key]').forEach(textarea => {
            if (textarea.ckeditorInstance) {
                textarea.value = textarea.ckeditorInstance.getData();
            }
        });
    }
    
    function saveSingleSetting(key, value, activeTabGroup = '') {
        const formData = new FormData();
        formData.append('settings[' + key + ']', value);
        formData.append('_token', csrfToken);
        formData.append('active_tab', activeTabGroup);
        
        fetch('{{ route("admin.settings.updateMultiple") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Setting ' + key + ' saved successfully');
                showNotification('success', 'Setting tersimpan otomatis');
                
                if (data.active_tab) {
                    const url = new URL(window.location);
                    url.searchParams.set('tab', data.active_tab);
                    window.history.pushState({}, '', url);
                }
            }
        })
        .catch(error => console.error('Error saving setting:', error));
    }
    
    function showNotification(type, message) {
        document.querySelectorAll('.notification').forEach(notification => notification.remove());
        
        const notification = document.createElement('div');
        notification.className = `notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white max-w-sm`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-3"></i>
                <div><p class="font-medium">${message}</p></div>
                <button type="button" class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 5000);
    }
    
    // File upload handlers
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
            formData.append('_token', csrfToken);
            
            const progressContainer = document.querySelector(`.upload-progress-${settingKey.replace(/\s+/g, '_')}`);
            const progressBar = progressContainer.querySelector('.progress-bar');
            progressContainer.classList.remove('hidden');
            
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Uploading...';
            
            fetch('{{ route("admin.settings.uploadFile") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                let progress = 0;
                const interval = setInterval(() => {
                    progress += 10;
                    progressBar.style.width = progress + '%';
                    if (progress >= 90) clearInterval(interval);
                }, 100);
                
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById(settingKey.replace(/\s+/g, '_')).value = data.file_url;
                    
                    const previewContainer = fileInput.previousElementSibling;
                    if (previewContainer && previewContainer.tagName === 'DIV') {
                        previewContainer.innerHTML = `
                            <img src="${data.file_url}"
                                 alt="${settingKey.replace(/_/g, ' ')}"
                                 class="h-16 w-auto inline-block"
                                 onerror="this.style.display='none'; document.getElementById('no-preview-${settingKey.replace(/\s+/g, '_')}').style.display='block';">
                            <span id="no-preview-${settingKey.replace(/\s+/g, '_')}" class="text-sm text-gray-500 hidden">Preview not available</span>
                        `;
                    }
                    
                    let removeBtn = document.querySelector(`.remove-file-btn[data-setting-key="${settingKey}"]`);
                    if (!removeBtn) {
                        removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'remove-file-btn bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition';
                        removeBtn.dataset.settingKey = settingKey;
                        removeBtn.innerHTML = '<i class="fas fa-trash mr-1"></i> Remove';
                        this.parentElement.appendChild(removeBtn);
                        removeBtn.addEventListener('click', handleRemoveFile);
                    }
                    removeBtn.classList.remove('hidden');
                    
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
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-upload mr-1"></i> Upload';
                progressContainer.classList.add('hidden');
                progressBar.style.width = '0%';
                fileInput.value = '';
            });
        });
    });
    
    // Remove file handlers
    document.querySelectorAll('.remove-file-btn').forEach(button => {
        button.addEventListener('click', handleRemoveFile);
    });
    
    function handleRemoveFile() {
        const settingKey = this.dataset.settingKey;
        
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Removing...';
        
        const formData = new FormData();
        formData.append('setting_key', settingKey);
        formData.append('_token', csrfToken);
        
        fetch('{{ route("admin.settings.removeFile") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(settingKey.replace(/\s+/g, '_')).value = '';
                
                const fileInput = document.getElementById(`file-${settingKey.replace(/\s+/g, '_')}`);
                const previewContainer = fileInput.previousElementSibling;
                if (previewContainer && previewContainer.tagName === 'DIV') {
                    previewContainer.innerHTML = '';
                }
                
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
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-trash mr-1"></i> Remove';
        });
    }
    
    // Initialize CKEditor for textarea fields
    if (typeof ClassicEditor !== 'undefined') {
        document.querySelectorAll('textarea[data-setting-key]').forEach(textarea => {
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
                    textarea.ckeditorInstance = editor;
                    
                    editor.model.document.on('change:data', () => {
                        textarea.value = editor.getData();
                    });
                })
                .catch(error => {
                    console.error('CKEditor initialization error:', error);
                });
        });
    }
});
</script>

<style>
.ck-content ul, .ck-content ol {
    padding-left: 20px !important;
    margin-left: 0 !important;
}

.ck-content li {
    margin-left: 0 !important;
    padding-left: 0 !important;
}

.ck-editor__editable {
    min-height: 200px;
}
</style>
@endpush
@endsection