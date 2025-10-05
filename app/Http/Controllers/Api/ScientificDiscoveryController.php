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
        $discoveries = ScientificDiscovery::with(['event'])->get();
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
            'discovery_date' => 'required|date',
            'related_event_id' => 'nullable|exists:gravitational_wave_events,id',
        ], [
            'title.required' => 'O título é obrigatório.',
            'discovery_date.required' => 'A data da descoberta é obrigatória.',
            'discovery_date.date' => 'A data da descoberta é inválida.',
            'related_event_id.exists' => 'O evento relacionado informado não existe.',
        ]);

        $discovery = ScientificDiscovery::create($validated);
        return response()->json($discovery, 201);
    }

    /**
     * Mostrar uma descoberta científica
     */
    public function show($id)
    {
        $discovery = ScientificDiscovery::with(['event'])->findOrFail($id);
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
            'discovery_date' => 'sometimes|required|date',
            'related_event_id' => 'nullable|exists:gravitational_wave_events,id',
        ], [
            'discovery_date.date' => 'A data da descoberta é inválida.',
            'related_event_id.exists' => 'O evento relacionado informado não existe.',
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
