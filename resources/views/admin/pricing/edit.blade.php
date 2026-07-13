@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Plan</h1>
    <div class="card shadow mb-4"><div class="card-body">
        <form action="{{ route('admin.pricing.update', $pricing) }}" method="POST">
            @csrf
            <div class="form-group"><label>Name (EN)</label><input type="text" name="name_en" class="form-control" value="{{ $pricing->name['en'] ?? '' }}" required></div>
            <div class="form-group"><label>Price</label><input type="text" name="price" class="form-control" value="{{ $pricing->price }}"></div>
            <div class="form-group"><label>Billing Cycle</label><input type="text" name="billing_cycle" class="form-control" value="{{ $pricing->billing_cycle }}"></div>
            <div class="form-group"><label>Features (EN) - One per line</label><textarea name="features_en" class="form-control" rows="5">{{ is_array($pricing->features['en'] ?? '') ? implode("\n", $pricing->features['en'] ?? []) : ($pricing->features['en'] ?? '') }}</textarea></div>
            <div class="form-group form-check"><input type="checkbox" name="is_featured" class="form-check-input" value="1" {{ $pricing->is_featured ? 'checked' : '' }}> <label class="form-check-label">Featured</label></div>
            <div class="form-group form-check"><input type="checkbox" name="status" class="form-check-input" value="active" {{ $pricing->status ? 'checked' : '' }}> <label class="form-check-label">Active</label></div>
            <button class="btn btn-primary">Update</button>
        </form>
    </div></div>
</div>
@endsection
