<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserClassification;
use Illuminate\Validation\Rule;

class UserClassificationController extends Controller
{
    /**
     * Listar todas as classificações de usuários
     */
    public function index()
    {
        $classifications = UserClassification::all();
        return response()->json($classifications);
    }

    /**
     * Criar uma nova classificação de usuário
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'classification' => 'required|string|max:100',
        ], [
            'user_id.required' => 'O usuário é obrigatório.',
            'user_id.exists' => 'O usuário informado não existe.',
            'classification.required' => 'A classificação é obrigatória.',
        ]);

        $classification = UserClassification::create($validated);
        return response()->json($classification, 201);
    }

    /**
     * Mostrar uma classificação
     */
    public function show($id)
    {
        $classification = UserClassification::findOrFail($id);
        return response()->json($classification);
    }

    /**
     * Atualizar uma classificação
     */
    public function update(Request $request, $id)
    {
        $classification = UserClassification::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'classification' => 'sometimes|required|string|max:100',
        ], [
            'user_id.exists' => 'O usuário informado não existe.',
        ]);

        $classification->update($validated);
        return response()->json($classification);
    }

    /**
     * Deletar uma classificação
     */
    public function destroy($id)
    {
        $classification = UserClassification::findOrFail($id);
        $classification->delete();
        return response()->json(['message' => 'Classificação removida com sucesso.']);
    }
}
