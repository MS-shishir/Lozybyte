@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Add Homepage Section</h1>
    <div class="card shadow mb-4"><div class="card-body">
        <form action="{{ route('admin.homepage-sections.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group"><label>Section Key (e.g. hero, services)</label><input type="text" name="key" class="form-control" required></div>
            
            <div class="row">
                <div class="col-md-4"><div class="form-group"><label>Title (EN)</label><input type="text" name="title_en" class="form-control"></div></div>
                <div class="col-md-4"><div class="form-group"><label>Title (BN)</label><input type="text" name="title_bn" class="form-control"></div></div>

            </div>

            <div class="row">
                <div class="col-md-4"><div class="form-group"><label>Subtitle (EN)</label><input type="text" name="subtitle_en" class="form-control"></div></div>
                <div class="col-md-4"><div class="form-group"><label>Subtitle (BN)</label><input type="text" name="subtitle_bn" class="form-control"></div></div>

            </div>

            <div class="row">
                <div class="col-md-4"><div class="form-group"><label>Description (EN)</label><textarea name="description_en" class="form-control" rows="3"></textarea></div></div>
                <div class="col-md-4"><div class="form-group"><label>Description (BN)</label><textarea name="description_bn" class="form-control" rows="3"></textarea></div></div>

            </div>

            <div class="row">
                <div class="col-md-4"><div class="form-group"><label>Button Text (EN)</label><input type="text" name="button_text_en" class="form-control"></div></div>
                <div class="col-md-4"><div class="form-group"><label>Button Text (BN)</label><input type="text" name="button_text_bn" class="form-control"></div></div>

            </div>

            <div class="form-group"><label>Button URL</label><input type="text" name="button_url" class="form-control"></div>
            
            <div class="form-group"><label>Background Image / Video URL</label><input type="file" name="background_image" class="form-control-file"></div>
            <div class="form-group"><label>Main Image</label><input type="file" name="main_image" class="form-control-file"></div>
            
            <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" class="form-control" value="0"></div>
            <div class="form-group form-check"><input type="checkbox" name="visible" class="form-check-input" value="active" checked> <label class="form-check-label">Visible</label></div>
            <button class="btn btn-primary">Save Section</button>
        </form>
    </div></div>
</div>
@endsection
