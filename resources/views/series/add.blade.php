@extends('layout')

@section('header')
Add Serie
@endsection

@section('content')

@include('errors', ['errors' => $errors])

<form method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col col-8">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name">
        </div>
        <div class="col col-2">
            <label for="seasons_qty">Number of seasons</label>
            <input type="number" class="form-control" name="seasons_qty" id="seasons_qty">
        </div>
        <div class="col col-2">
            <label for="episodes_qty">Number of episodes</label>
            <input type="number" class="form-control" name="episodes_qty" id="episodes_qty">
        </div>
    </div>
    <div class="row">
        <div class="col col-12">
            <label for="cover">Cover</label>
            <input type="file" name="cover" id="cover" class="form-control">
        </div>
    </div>

    <button class="btn btn-primary mt-2">Add</button>
</form>
@endsection