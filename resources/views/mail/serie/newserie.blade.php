@component('mail::message')
# New Serie
### Hello {{ $username }}, a new serie was succesfullly added:
### Serie Name: {{ $name }}
### Number of Seasons: {{ $seasonsQty }}
### Number of Episodes: {{ $episodesQty }}
@endcomponent