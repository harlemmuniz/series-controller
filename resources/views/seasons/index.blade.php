@extends('layout')

@section('header')
{{$serie->name}}'s Seasons
@endsection

@section('content')
@if($serie->cover)
<div class="row mb-4">
    <div class="col-md-12 text-center">
        <a href="{{$serie->cover_url}}" target="_blank">
            <img src="{{$serie->cover_url}}" class="img-thumbnail" alt="Serie Logo" height="300px" width="300px">
        </a>
    </div>
</div>
@endif
@foreach($seasons as $season)

<li class="list-group-item d-flex justify-content-between align-items-center">
    <a href="/seasons/{{ $season->id }}/episodes">
        Season {{$season->number}}
    </a>
    <span class="badge badge-secondary">
        {{ $season->getWatchedEpisodes()->count() }} / {{ $season->episodes->count() }}
    </span>
</li>
@endforeach

@endsection