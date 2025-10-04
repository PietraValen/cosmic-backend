<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectStatistic;
use Illuminate\Validation\Rule;

class ProjectStatisticsController extends Controller
{
    /**
     * Listar todas as estatísticas de projetos
     */
    public function index()
    {
        $stats = ProjectStatistic::all();
        return response()->json($stats);
    }

    /**
     * Criar uma nova estatística de projeto
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'glitches_detected' => 'nullable|integer|min:0',
            'discoveries_made' => 'nullable|integer|min:0',
            'completed_at' => 'nullable|date',
        ], [
            'project_name.required' => 'O nome do projeto é obrigatório.',
            'glitches_detected.integer' => 'O número de glitches detectados deve ser um inteiro.',
            'glitches_detected.min' => 'O número de glitches detectados não pode ser negativo.',
            'discoveries_made.integer' => 'O número de descobertas deve ser um inteiro.',
            'discoveries_made.min' => 'O número de descobertas não pode ser negativo.',
            'completed_at.date' => 'A data de conclusão é inválida.',
        ]);

        $stat = ProjectStatistic::create($validated);
        return response()->json($stat, 201);
    }

    /**
     * Mostrar uma estatística de projeto
     */
    public function show($id)
    {
        $stat = ProjectStatistic::findOrFail($id);
        return response()->json($stat);
    }

    /**
     * Atualizar uma estatística de projeto
     */
    public function update(Request $request, $id)
    {
        $stat = ProjectStatistic::findOrFail($id);

        $validated = $request->validate([
            'project_name' => 'sometimes|required|string|max:255',
            'glitches_detected' => 'nullable|integer|min:0',
            'discoveries_made' => 'nullable|integer|min:0',
            'completed_at' => 'nullable|date',
        ], [
            'glitches_detected.integer' => 'O número de glitches detectados deve ser um inteiro.',
            'glitches_detected.min' => 'O número de glitches detectados não pode ser negativo.',
            'discoveries_made.integer' => 'O número de descobertas deve ser um inteiro.',
            'discoveries_made.min' => 'O número de descobertas não pode ser negativo.',
            'completed_at.date' => 'A data de conclusão é inválida.',
        ]);

        $stat->update($validated);
        return response()->json($stat);
    }

    /**
     * Deletar uma estatística de projeto
     */
    public function destroy($id)
    {
        $stat = ProjectStatistic::findOrFail($id);
        $stat->delete();
        return response()->json(['message' => 'Estatística de projeto removida com sucesso.']);
    }
}
