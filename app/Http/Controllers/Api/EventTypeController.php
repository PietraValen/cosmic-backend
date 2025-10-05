<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventType;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
    /**
     * Listar todos os tipos de evento
     */
    public function index()
    {
        $event = EventType::all();
        return response()->json($event);
    }

    /**
     * Criar um novo tipo de evento
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:event_types,name',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.unique' => 'Este nome de tipo de evento já está em uso.',
            'description.string' => 'A descrição deve ser uma string.',
        ]);

        $event = EventType::create($validated);
        return response()->json($event, 201);
    }

    /**
     * Mostrar um tipo de evento
     */
    public function show($id)
    {
        $event = EventType::findOrFail($id);
        return response()->json($event);
    }

    /**
     * Atualizar um tipo de evento
     */
    public function update(Request $request, $id)
    {
        $event = EventType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|unique:event_types,name,' . $id,
            'description' => 'nullable|string',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.unique' => 'Este nome de tipo de evento já está em uso.',
            'description.string' => 'A descrição deve ser uma string.',
        ]);

        $event->update($validated);
        return response()->json($event);
    }

    /**
     * Deletar um tipo de evento
     */
    public function destroy($id)
    {
        $event = EventType::findOrFail($id);
        $event->delete();
        return response()->json(['message' => 'Tipo de evento removido com sucesso.']);
    }
}
