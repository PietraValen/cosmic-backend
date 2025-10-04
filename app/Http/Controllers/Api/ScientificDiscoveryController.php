<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ScientificDiscovery;
use Illuminate\Validation\Rule;

class ScientificDiscoveryController extends Controller
{
    /**
     * Listar todas as descobertas científicas
     */
    public function index()
    {
        $discoveries = ScientificDiscovery::all();
        return response()->json($discoveries);
    }

    /**
     * Criar uma nova descoberta científica
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'detected_at' => 'required|date',
            'related_glitch_id' => 'nullable|exists:glitches,id',
        ], [
            'title.required' => 'O título é obrigatório.',
            'detected_at.required' => 'A data da descoberta é obrigatória.',
            'detected_at.date' => 'A data da descoberta é inválida.',
            'related_glitch_id.exists' => 'O glitch relacionado informado não existe.',
        ]);

        $discovery = ScientificDiscovery::create($validated);
        return response()->json($discovery, 201);
    }

    /**
     * Mostrar uma descoberta científica
     */
    public function show($id)
    {
        $discovery = ScientificDiscovery::findOrFail($id);
        return response()->json($discovery);
    }

    /**
     * Atualizar uma descoberta científica
     */
    public function update(Request $request, $id)
    {
        $discovery = ScientificDiscovery::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'detected_at' => 'sometimes|required|date',
            'related_glitch_id' => 'nullable|exists:glitches,id',
        ], [
            'detected_at.date' => 'A data da descoberta é inválida.',
            'related_glitch_id.exists' => 'O glitch relacionado informado não existe.',
        ]);

        $discovery->update($validated);
        return response()->json($discovery);
    }

    /**
     * Deletar uma descoberta científica
     */
    public function destroy($id)
    {
        $discovery = ScientificDiscovery::findOrFail($id);
        $discovery->delete();
        return response()->json(['message' => 'Descoberta científica removida com sucesso.']);
    }
}
