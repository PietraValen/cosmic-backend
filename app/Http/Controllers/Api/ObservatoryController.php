<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Observatory;
use Illuminate\Validation\Rule;

class ObservatoryController extends Controller
{
    /**
     * Listar todos os observatórios
     */
    public function index()
    {
        $observatories = Observatory::all();
        return response()->json($observatories);
    }

    /**
     * Criar um novo observatório
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:observatories,name',
            'code' => 'required|string|max:50|unique:observatories,code',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'location' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'status' => ['nullable', Rule::in(['active', 'inactive', 'maintenance'])],
            'operational_since' => 'nullable|date',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20',
        ], [
            'name.required' => 'O nome do observatório é obrigatório.',
            'name.unique' => 'Este nome de observatório já existe.',
            'code.required' => 'O código do observatório é obrigatório.',
            'code.unique' => 'Este código já está em uso.',
            'latitude.between' => 'A latitude deve estar entre -90 e 90.',
            'longitude.between' => 'A longitude deve estar entre -180 e 180.',
            'status.in' => 'O status informado é inválido.',
        ]);

        $observatory = Observatory::create($validated);
        return response()->json($observatory, 201);
    }

    /**
     * Mostrar um observatório
     */
    public function show($id)
    {
        $observatory = Observatory::findOrFail($id);
        return response()->json($observatory);
    }

    /**
     * Atualizar um observatório
     */
    public function update(Request $request, $id)
    {
        $observatory = Observatory::findOrFail($id);

        $validated = $request->validate([
            'name' => ['sometimes','required','string','max:255', Rule::unique('observatories','name')->ignore($observatory->id)],
            'code' => ['sometimes','required','string','max:50', Rule::unique('observatories','code')->ignore($observatory->id)],
            'latitude' => 'sometimes|required|numeric|between:-90,90',
            'longitude' => 'sometimes|required|numeric|between:-180,180',
            'location' => 'sometimes|required|string|max:255',
            'country' => 'sometimes|required|string|max:100',
            'status' => ['nullable', Rule::in(['active', 'inactive', 'maintenance'])],
            'operational_since' => 'nullable|date',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20',
        ], [
            'name.unique' => 'Este nome de observatório já existe.',
            'code.unique' => 'Este código já está em uso.',
            'latitude.between' => 'A latitude deve estar entre -90 e 90.',
            'longitude.between' => 'A longitude deve estar entre -180 e 180.',
            'status.in' => 'O status informado é inválido.',
        ]);

        $observatory->update($validated);
        return response()->json($observatory);
    }

    /**
     * Deletar um observatório
     */
    public function destroy($id)
    {
        $observatory = Observatory::findOrFail($id);
        $observatory->delete();
        return response()->json(['message' => 'Observatório removido com sucesso.']);
    }
}
