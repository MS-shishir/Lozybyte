@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Homepage Sections</h1>
        <a href="{{ route('admin.homepage-sections.create') }}" class="btn btn-primary shadow-sm"><i class="fas fa-plus"></i> Add Section</a>
    </div>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table table-bordered">
                <tr><th>Key</th><th>Title (EN)</th><th>Order</th><th>Visibility</th><th>Actions</th></tr>
                @foreach($sections as $section)
                <tr>
                    <td><code>{{ $section->key }}</code></td>
                    <td>{{ $section->title['en'] ?? 'N/A' }}</td>
                    <td>{{ $section->sort_order }}</td>
                    <td>
                        @if($section->visible)
                            <span class="badge badge-success">Visible</span>
                        @else
                            <span class="badge badge-secondary">Hidden</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.homepage-sections.edit', $section) }}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{ route('admin.homepage-sections.destroy', $section) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this Section?')">
                            @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection
