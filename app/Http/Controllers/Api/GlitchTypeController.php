<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GlitchType;
use Illuminate\Validation\Rule;

class GlitchTypeController extends Controller
{
    /**
     * Listar todos os tipos de glitch
     */
    public function index()
    {
        $types = GlitchType::all();
        return response()->json($types);
    }

    /**
     * Criar um novo tipo de glitch
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:glitch_types,name',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20',
        ], [
            'name.required' => 'O nome do tipo de glitch é obrigatório.',
            'name.unique' => 'Este nome já está em uso.',
        ]);

        $type = GlitchType::create($validated);
        return response()->json($type, 201);
    }

    /**
     * Mostrar um tipo de glitch
     */
    public function show($id)
    {
        $type = GlitchType::findOrFail($id);
        return response()->json($type);
    }

    /**
     * Atualizar um tipo de glitch
     */
    public function update(Request $request, $id)
    {
        $type = GlitchType::findOrFail($id);

        $validated = $request->validate([
            'name' => ['sometimes','required','string','max:255', Rule::unique('glitch_types','name')->ignore($type->id)],
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20',
        ], [
            'name.unique' => 'Este nome já está em uso.',
        ]);

        $type->update($validated);
        return response()->json($type);
    }

    /**
     * Deletar um tipo de glitch
     */
    public function destroy($id)
    {
        $type = GlitchType::findOrFail($id);
        $type->delete();
        return response()->json(['message' => 'Tipo de glitch removido com sucesso.']);
    }
}
