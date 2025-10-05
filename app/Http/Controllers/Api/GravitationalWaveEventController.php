<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GravitationalWaveEvent;
use Illuminate\Validation\Rule;

class GravitationalWaveEventController extends Controller
{
    /**
     * Listar todos os eventos gravitacionais
     */
    public function index()
    {
        $events = GravitationalWaveEvent::all();
        return response()->json($events);
    }

    /**
     * Criar um novo evento gravitacional
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:gravitational_wave_events,name',
            'event_date' => 'required|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'event_type' => 'required|integer|exists:event_types,id',
            'mass_1' => 'nullable|numeric|min:0',
            'mass_2' => 'nullable|numeric|min:0',
            'distance_mpc' => 'nullable|numeric|min:0',
            'false_alarm_rate' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'significance' => 'nullable|string',
            'detectors' => 'nullable|array',
            'color' => 'nullable|string|max:20',
        ], [
            'name.required' => 'O nome do evento é obrigatório.',
            'name.unique' => 'Este nome de evento já existe.',
            'event_date.required' => 'A data do evento é obrigatória.',
            'latitude.between' => 'A latitude deve estar entre -90 e 90.',
            'longitude.between' => 'A longitude deve estar entre -180 e 180.',
        ]);

        $event = GravitationalWaveEvent::create($validated);
        return response()->json($event, 201);
    }

    /**
     * Mostrar um evento
     */
    public function show($id)
    {
        $event = GravitationalWaveEvent::findOrFail($id);
        return response()->json($event);
    }

    /**
     * Atualizar um evento
     */
    public function update(Request $request, $id)
    {
        $event = GravitationalWaveEvent::findOrFail($id);

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('gravitational_wave_events', 'name')->ignore($event->id)],
            'event_date' => 'sometimes|required|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'event_type' => 'required|integer|exists:event_types,id',
            'mass_1' => 'nullable|numeric|min:0',
            'mass_2' => 'nullable|numeric|min:0',
            'distance_mpc' => 'nullable|numeric|min:0',
            'false_alarm_rate' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'significance' => 'nullable|string',
            'detectors' => 'nullable|array',
            'color' => 'nullable|string|max:20',
        ], [
            'name.unique' => 'Este nome de evento já existe.',
            'latitude.between' => 'A latitude deve estar entre -90 e 90.',
            'longitude.between' => 'A longitude deve estar entre -180 e 180.',
        ]);

        $event->update($validated);
        return response()->json($event);
    }

    /**
     * Deletar um evento
     */
    public function destroy($id)
    {
        $event = GravitationalWaveEvent::findOrFail($id);
        $event->delete();
        return response()->json(['message' => 'Evento removido com sucesso.']);
    }
}
