<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Glitch;
use Illuminate\Validation\Rule;

class GlitchController extends Controller
{
    /**
     * Listar todos os glitches
     */
    public function index()
    {
        $glitches = Glitch::all();
        return response()->json($glitches);
    }

    /**
     * Criar um novo glitch
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'detector_id' => 'required|exists:detectors,id',
            'observatory_id' => 'required|exists:observatories,id',
            'glitch_type_id' => 'required|exists:glitch_types,id',
            'description' => 'nullable|string',
            'severity' => ['nullable', Rule::in(['low','medium','high'])],
            'detected_at' => 'required|date',
        ], [
            'title.required' => 'O título do glitch é obrigatório.',
            'detector_id.required' => 'O detector é obrigatório.',
            'detector_id.exists' => 'O detector informado não existe.',
            'observatory_id.required' => 'O observatório é obrigatório.',
            'observatory_id.exists' => 'O observatório informado não existe.',
            'glitch_type_id.required' => 'O tipo de glitch é obrigatório.',
            'glitch_type_id.exists' => 'O tipo de glitch informado não existe.',
            'detected_at.required' => 'A data de detecção é obrigatória.',
            'detected_at.date' => 'A data de detecção é inválida.',
            'severity.in' => 'O nível de severidade é inválido.',
        ]);

        $glitch = Glitch::create($validated);
        return response()->json($glitch, 201);
    }

    /**
     * Mostrar um glitch
     */
    public function show($id)
    {
        $glitch = Glitch::findOrFail($id);
        return response()->json($glitch);
    }

    /**
     * Atualizar um glitch
     */
    public function update(Request $request, $id)
    {
        $glitch = Glitch::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'detector_id' => 'sometimes|required|exists:detectors,id',
            'observatory_id' => 'sometimes|required|exists:observatories,id',
            'glitch_type_id' => 'sometimes|required|exists:glitch_types,id',
            'description' => 'nullable|string',
            'severity' => ['nullable', Rule::in(['low','medium','high'])],
            'detected_at' => 'sometimes|required|date',
        ], [
            'detector_id.exists' => 'O detector informado não existe.',
            'observatory_id.exists' => 'O observatório informado não existe.',
            'glitch_type_id.exists' => 'O tipo de glitch informado não existe.',
            'detected_at.date' => 'A data de detecção é inválida.',
            'severity.in' => 'O nível de severidade é inválido.',
        ]);

        $glitch->update($validated);
        return response()->json($glitch);
    }

    /**
     * Deletar um glitch
     */
    public function destroy($id)
    {
        $glitch = Glitch::findOrFail($id);
        $glitch->delete();
        return response()->json(['message' => 'Glitch removido com sucesso.']);
    }
}
