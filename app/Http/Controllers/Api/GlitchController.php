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
        $glitches = Glitch::with(['detector', 'glitchType', 'validatedByUser'])->get();
        return response()->json($glitches);
    }

    /**
     * Criar um novo glitch
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'detector_id' => 'required|exists:detectors,id',
            'glitch_type_id' => 'nullable|exists:glitch_types,id',
            'timestamp' => 'required|date',
            'peak_frequency' => 'nullable|numeric',
            'snr' => 'nullable|numeric',
            'duration' => 'nullable|numeric',
            'confidence' => 'nullable|numeric|between:0,1',
            'classification_method' => 'nullable|in:ai,human,hybrid',
            'spectrogram_url' => 'nullable|url',
            'notes' => 'nullable|string',
            'validated' => 'boolean',
            'validated_by' => 'nullable|exists:users,id',
            'validated_at' => 'nullable|date',
        ], [
            'detector_id.required' => 'O detector é obrigatório.',
            'detector_id.exists' => 'O detector informado não existe.',
            'glitch_type_id.exists' => 'O tipo de glitch informado não existe.',
            'timestamp.required' => 'A data e hora do glitch é obrigatória.',
            'timestamp.date' => 'A data e hora do glitch devem estar em um formato válido.',
            'peak_frequency.numeric' => 'A frequência de pico deve ser um número.',
            'snr.numeric' => 'A razão sinal-ruído (SNR) deve ser um número.',
            'duration.numeric' => 'A duração deve ser um número.',
            'confidence.numeric' => 'A confiança deve ser um número.',
            'confidence.between' => 'A confiança deve estar entre 0 e 1.',
            'classification_method.in' => 'O método de classificação deve ser: ai, human ou hybrid.',
            'spectrogram_url.url' => 'A URL do espectrograma deve ser válida.',
            'notes.string' => 'As observações devem ser um texto.',
            'validated.boolean' => 'O campo de validação deve ser verdadeiro ou falso.',
            'validated_by.exists' => 'O usuário validador informado não existe.',
            'validated_at.date' => 'A data de validação deve estar em um formato válido.',
        ]);

        $glitch = Glitch::create($validated);
        return response()->json($glitch, 201);
    }

    /**
     * Mostrar um glitch
     */
    public function show($id)
    {
        $glitch = Glitch::with(['detector', 'glitchType', 'validatedByUser'])->findOrFail($id);
        return response()->json($glitch);
    }

    /**
     * Atualizar um glitch
     */
    public function update(Request $request, $id)
    {
        $glitch = Glitch::findOrFail($id);

        $validated = $request->validate([
            'detector_id' => 'sometimes|required|exists:detectors,id',
            'glitch_type_id' => 'sometimes|nullable|exists:glitch_types,id',
            'timestamp' => 'sometimes|required|date',
            'peak_frequency' => 'nullable|numeric',
            'snr' => 'nullable|numeric',
            'duration' => 'nullable|numeric',
            'confidence' => 'nullable|numeric|between:0,1',
            'classification_method' => 'nullable|in:ai,human,hybrid',
            'spectrogram_url' => 'nullable|url',
            'notes' => 'nullable|string',
            'validated' => 'nullable|boolean',
            'validated_by' => 'nullable|exists:users,id',
            'validated_at' => 'nullable|date',
        ], [
            'detector_id.required' => 'O detector é obrigatório.',
            'detector_id.exists' => 'O detector informado não existe.',
            'glitch_type_id.exists' => 'O tipo de glitch informado não existe.',
            'timestamp.required' => 'A data e hora do glitch é obrigatória.',
            'timestamp.date' => 'A data e hora do glitch devem estar em um formato válido.',
            'peak_frequency.numeric' => 'A frequência de pico deve ser um número.',
            'snr.numeric' => 'A razão sinal-ruído (SNR) deve ser um número.',
            'duration.numeric' => 'A duração deve ser um número.',
            'confidence.numeric' => 'A confiança deve ser um número.',
            'confidence.between' => 'A confiança deve estar entre 0 e 1.',
            'classification_method.in' => 'O método de classificação deve ser: ai, human ou hybrid.',
            'spectrogram_url.url' => 'A URL do espectrograma deve ser válida.',
            'notes.string' => 'As observações devem ser um texto.',
            'validated.boolean' => 'O campo de validação deve ser verdadeiro ou falso.',
            'validated_by.exists' => 'O usuário validador informado não existe.',
            'validated_at.date' => 'A data de validação deve estar em um formato válido.',
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
