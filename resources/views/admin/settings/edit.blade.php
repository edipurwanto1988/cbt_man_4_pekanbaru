@extends('layouts.admin')

@section('title', 'Edit Setting')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Setting</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update', $setting->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="key" class="form-label">Key</label>
                                    <input type="text" 
                                           class="form-control @error('key') is-invalid @enderror" 
                                           id="key" 
                                           name="key" 
                                           value="{{ old('key', $setting->key) }}" 
                                           required>
                                    @error('key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="group" class="form-label">Group</label>
                                    <select class="form-select @error('group') is-invalid @enderror" 
                                            id="group" 
                                            name="group" 
                                            required>
                                        <option value="">Pilih Group</option>
                                        @foreach($groups as $group)
                                            <option value="{{ $group }}" {{ old('group', $setting->group) == $group ? 'selected' : '' }}>
                                                {{ $group }}
                                            </option>
                                        @endforeach
                                        <option value="Custom Group" {{ old('group', $setting->group) == 'Custom Group' ? 'selected' : '' }}>
                                            Custom Group
                                        </option>
                                    </select>
                                    @error('group')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="value" class="form-label">Value</label>
                            <textarea class="form-control @error('value') is-invalid @enderror" 
                                      id="value" 
                                      name="value" 
                                      rows="4" 
                                      required>{{ old('value', $setting->value) }}</textarea>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update
                            </button>
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection