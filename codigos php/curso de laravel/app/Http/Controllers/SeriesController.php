<?php

namespace App\Http\Controllers;

use App\Models\Series;
use App\Models\Season;
use App\Models\Episode;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index(Request $request)
    {
        $series = Series::with('temporadas')->get();
        $mensagemSucesso = session()->get('mensagem.sucesso');

        return view('series.index')->with('series', $series)->with('mensagemSucesso', $mensagemSucesso);
    }

    public function create()
    {
        return view('series.create');
    }

    public function store(Request $request)
    {
        // Validação manual
        $request->validate([
            'nome' => 'required|min:3|max:255',
            'seasonsQty' => 'required|integer|min:1',
            'episodesPerSeason' => 'required|integer|min:1'
        ]);

        // Criar apenas a série com o nome
        $series = Series::create([
            'nome' => $request->input('nome')
        ]);

        $seasons = [];
        for ($i = 0; $i < $request->input('seasonsQty'); $i++) {
            $seasons[] = [
                'serie_id' => $series->id,
                'number' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Season::insert($seasons);

        // Recarregar as temporadas para obter os IDs
        $createdSeasons = Season::where('serie_id', $series->id)->get();

        $episodes = [];
        foreach ($createdSeasons as $season) {
            for ($j = 1; $j <= $request->input('episodesPerSeason'); $j++) {
                $episodes[] = [
                    'season_id' => $season->id,
                    'number' => $j,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Episode::insert($episodes);

        return to_route('series.index')
                ->with('mensagem.sucesso', "Série '{$series->nome}' adicionada com sucesso!");
    }

    public function destroy(Series $series)
    {
        $series->delete();

        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '{$series->nome}' removida com sucesso!");
    }

    public function edit(Series $series)
    {

        return view('series.edit')->with('serie', $series);
    }
    public function update(Series $series, Request $request)
    {
        // Validação manual
        $request->validate([
            'nome' => 'required|min:3|max:255'
        ]);

        $series->fill($request->all());
        $series->save();

        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '{$series->nome}' atualizada com sucesso!");
    }

}
