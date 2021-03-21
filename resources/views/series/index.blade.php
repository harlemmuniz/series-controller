@extends('layout')

@section('header')
Series
@endsection

@section('content')

@include('message', ['message' => $message])

@auth
<a href="{{ route('form_add_serie') }}" class="btn btn-dark mb-2">Add</a>
@endauth

<ul class="list-group">
    @foreach($series as $serie)
    <li class="list-group-item d-flex justify-content-between align-items-center">

        <div>
            <a href="/series/{{$serie->id}}/seasons">
                <img src="{{$serie->cover_url}}" class="img-thumbnail" alt="Serie Logo" height="100px" width="100px">
            </a>
            <a href="/series/{{$serie->id}}/seasons">
                <span id="name-serie-{{$serie->id}}"><b>{{$serie->name}}</b></span>
            </a>
        </div>

        <div class="input-group w-50" hidden id="input-name-serie-{{$serie->id}}">
            <input type="text" class="form-control" value="{{$serie->name}}">
            <div class="input-group-append">
                <button class="btn btn-primary" onclick="editSerie(`{{$serie->id}}`)">
                    <i class="fas fa-check"></i>
                </button>
                @csrf
            </div>
        </div>

        <span class="d-flex">
            <a href="/series/{{$serie->id}}/seasons" class="btn btn-info btn-sm mr-1">
                <i class="fa fa-external-link-alt"></i>
            </a>
            @auth
            <button class="btn btn-info btn-sm mr-1" onclick="toggleInput(`{{$serie->id}}`)">
                <i class="fas fa-edit"></i>
            </button>
            <form action="/series/{{$serie->id}}" method="post" onsubmit="return confirm('Are you sure that you want to delete {{addslashes($serie->name)}}?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">
                    <i class="far fa-trash-alt"></i>
                </button>
            </form>
            @endauth
        </span>

        @endforeach
</ul>
<script>
    function toggleInput(serieId) {
        const serieNameElement = document.getElementById(`name-serie-${serieId}`);
        const inputSerieElement = document.getElementById(`input-name-serie-${serieId}`);
        if (serieNameElement.hasAttribute('hidden')) {
            serieNameElement.removeAttribute('hidden');
            inputSerieElement.hidden = true;
        } else {
            inputSerieElement.removeAttribute('hidden');
            serieNameElement.hidden = true;
        }
    }

    function editSerie(serieId) {
        let formData = new FormData();
        const name = document.querySelector(`#input-name-serie-${serieId} > input`).value;
        const token = document.querySelector('input[name="_token"]').value;

        formData.append('name', name);
        formData.append('_token', token);

        const url = `/series/${serieId}/editname`;
        fetch(url, {
            body: formData,
            method: 'POST'
        }).then(() => {
            toggleInput(serieId);
            document.getElementById(`name-serie-${serieId}`).textContent = name;
        });
    }
</script>
@endsection