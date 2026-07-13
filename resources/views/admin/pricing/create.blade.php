@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Add Plan</h1>
    <div class="card shadow mb-4"><div class="card-body">
        <form action="{{ route('admin.pricing.store') }}" method="POST">
            @csrf
            <div class="form-group"><label>Name (EN)</label><input type="text" name="name_en" class="form-control" required></div>
            <div class="form-group"><label>Price</label><input type="text" name="price" class="form-control"></div>
            <div class="form-group"><label>Billing Cycle</label><input type="text" name="billing_cycle" class="form-control"></div>
            <div class="form-group"><label>Features (EN) - One per line</label><textarea name="features_en" class="form-control" rows="5"></textarea></div>
            <div class="form-group form-check"><input type="checkbox" name="is_featured" class="form-check-input" value="1"> <label class="form-check-label">Featured</label></div>
            <div class="form-group form-check"><input type="checkbox" name="status" class="form-check-input" value="active" checked> <label class="form-check-label">Active</label></div>
            <button class="btn btn-primary">Save</button>
        </form>
    </div></div>
</div>
@endsection
