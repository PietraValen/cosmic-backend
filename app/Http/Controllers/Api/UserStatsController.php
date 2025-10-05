<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserStat;
use Illuminate\Validation\Rule;

class UserStatsController extends Controller
{
    /**
     * Listar todas as estatísticas de usuários
     */
    public function index()
    {
        $stats = UserStat::with(['user'])->get();
        return response()->json($stats);
    }

    /**
     * Criar uma nova estatística de usuário
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'glitches_detected' => 'nullable|integer|min:0',
            'projects_completed' => 'nullable|integer|min:0',
            'score' => 'nullable|numeric|min:0',
        ], [
            'user_id.required' => 'O usuário é obrigatório.',
            'user_id.exists' => 'O usuário informado não existe.',
            'glitches_detected.integer' => 'O número de glitches detectados deve ser um inteiro.',
            'glitches_detected.min' => 'O número de glitches detectados não pode ser negativo.',
            'projects_completed.integer' => 'O número de projetos concluídos deve ser um inteiro.',
            'projects_completed.min' => 'O número de projetos concluídos não pode ser negativo.',
            'score.numeric' => 'A pontuação deve ser um número.',
            'score.min' => 'A pontuação não pode ser negativa.',
        ]);

        $stat = UserStat::create($validated);
        return response()->json($stat, 201);
    }

    /**
     * Mostrar uma estatística
     */
    public function show($id)
    {
        $stat = UserStat::with(['user'])->findOrFail($id);
        return response()->json($stat);
    }

    /**
     * Atualizar uma estatística
     */
    public function update(Request $request, $id)
    {
        $stat = UserStat::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'glitches_detected' => 'nullable|integer|min:0',
            'projects_completed' => 'nullable|integer|min:0',
            'score' => 'nullable|numeric|min:0',
        ], [
            'user_id.exists' => 'O usuário informado não existe.',
            'glitches_detected.integer' => 'O número de glitches detectados deve ser um inteiro.',
            'glitches_detected.min' => 'O número de glitches detectados não pode ser negativo.',
            'projects_completed.integer' => 'O número de projetos concluídos deve ser um inteiro.',
            'projects_completed.min' => 'O número de projetos concluídos não pode ser negativo.',
            'score.numeric' => 'A pontuação deve ser um número.',
            'score.min' => 'A pontuação não pode ser negativa.',
        ]);

        $stat->update($validated);
        return response()->json($stat);
    }

    /**
     * Deletar uma estatística
     */
    public function destroy($id)
    {
        $stat = UserStat::findOrFail($id);
        $stat->delete();
        return response()->json(['message' => 'Estatística removida com sucesso.']);
    }
}
