@extends('layouts.admin')

@section('title', 'Detail Setting')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Detail Setting</h5>
                    <div>
                        <a href="{{ route('admin.settings.edit', $setting->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 150px;">ID</th>
                                    <td>{{ $setting->id }}</td>
                                </tr>
                                <tr>
                                    <th>Key</th>
                                    <td>{{ $setting->key }}</td>
                                </tr>
                                <tr>
                                    <th>Group</th>
                                    <td>{{ $setting->group }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 150px;">Value</th>
                                    <td>{{ $setting->value }}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>{{ $setting->created_at->format('d-m-Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Diupdate</th>
                                    <td>{{ $setting->updated_at->format('d-m-Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection