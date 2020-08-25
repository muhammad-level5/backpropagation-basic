@extends('layouts.create')

@section('title', 'Create Data')
    
@section('content')
<div class="container-fluid">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </div>
    @endif
    <form action="{{ route('testing.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="upload">Upload Data Testing CSV</label>
            <input type="file" class="form-control-file" id="upload" name="file">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Upload</button>
        </div>
    </form>
</div>
@endsection