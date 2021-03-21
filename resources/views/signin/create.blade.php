@extends('layout')

@section('header')
Series - SignIn
@endsection

@section('content')

<form method="post">
    @csrf
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" required class="form-control">
    </div>

    <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" required class="form-control">
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required min="1" class="form-control">
    </div>

    <button class="btn btn-primary mt-3">
        SignIn
    </button>
</form>
@endsection