<x-layout title="Temporadas de {!! $series->nome !!}">
    <ul class="list-group">
        @foreach ($seasons as $season)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>
                    <a href="{{ route('episodes.index', $season->id) }}">
                        Temporada
                    </a>
                </span>

                <span class="badge bg-secondary">
                     {{ $season->numberofwatchedEpisodes }} / {{ $season->episodes()->count() }}
                </span>
                </span>
            </li>
        @endforeach
    </ul>
</x-layout>


