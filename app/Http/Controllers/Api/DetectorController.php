<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Detector;
use Illuminate\Validation\Rule;

class DetectorController extends Controller
{
    /**
     * Listar todos os detectores
     */
    public function index()
    {
        $detectors = Detector::all();
        return response()->json($detectors);
    }

    /**
     * Criar um novo detector
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:detectors,code',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'location' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'arm_length_km' => 'nullable|numeric|min:0',
            'status' => ['nullable', Rule::in(['active', 'inactive', 'maintenance'])],
            'operational_since' => 'nullable|date',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20',
        ], [
            'name.required' => 'O nome do detector é obrigatório.',
            'code.required' => 'O código do detector é obrigatório.',
            'code.unique' => 'Este código já está em uso.',
            'latitude.required' => 'A latitude é obrigatória.',
            'latitude.numeric' => 'A latitude deve ser um número.',
            'latitude.between' => 'A latitude deve estar entre -90 e 90.',
            'longitude.required' => 'A longitude é obrigatória.',
            'longitude.numeric' => 'A longitude deve ser um número.',
            'longitude.between' => 'A longitude deve estar entre -180 e 180.',
            'status.in' => 'O status informado é inválido.',
        ]);

        $detector = Detector::create($validated);
        return response()->json($detector, 201);
    }

    /**
     * Mostrar um detector
     */
    public function show($id)
    {
        $detector = Detector::findOrFail($id);
        return response()->json($detector);
    }

    /**
     * Atualizar um detector
     */
    public function update(Request $request, $id)
    {
        $detector = Detector::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => ['sometimes', 'required', 'string', 'max:50', Rule::unique('detectors', 'code')->ignore($detector->id)],
            'latitude' => 'sometimes|nullable|numeric|between:-90,90',
            'longitude' => 'sometimes|nullable|numeric|between:-180,180',
            'location' => 'sometimes|nullable|string|max:255',
            'country' => 'sometimes|nullable|string|max:100',
            'arm_length_km' => 'nullable|numeric|min:0',
            'status' => ['nullable', Rule::in(['active', 'inactive', 'maintenance'])],
            'operational_since' => 'nullable|date',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20',
        ], [
            'code.unique' => 'Este código já está em uso.',
            'latitude.between' => 'A latitude deve estar entre -90 e 90.',
            'longitude.between' => 'A longitude deve estar entre -180 e 180.',
            'status.in' => 'O status informado é inválido.',
        ]);

        $detector->update($validated);
        return response()->json($detector);
    }

    /**
     * Deletar um detector
     */
    public function destroy($id)
    {
        $detector = Detector::findOrFail($id);
        $detector->delete();
        return response()->json(['message' => 'Detector removido com sucesso.']);
    }
}
