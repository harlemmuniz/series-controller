@extends('layout')

@section('header')
Season {{$seasonId}} - Episodes
@endsection

@section('content')

@include('message', ['message' => $message])

<form action="/seasons/{{ $seasonId }}/episodes/watch" method="post">
    @csrf
    <ul class="list-group">
        @foreach($episodes as $episodes)

        <li class="list-group-item d-flex justify-content-between align-items-center">
            Episode {{ $episodes->number }}
            <input type="checkbox" name="episodes[]" value="{{ $episodes->id }}" {{ $episodes->watched ? 'checked' : '' }}>
        </li>
        @endforeach
    </ul>
    <button class="btn btn-primary mt-2 mb-5">Save</button>
</form>
@endsection