<?php

namespace App\Http\Controllers\Api\FaceEmbedding;

use App\Http\Controllers\Controller;
use App\Models\FaceEmbedding;
use Illuminate\Http\Request;

class FaceEmbeddingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mahasiswa_id' => 'required',
            'embedding' => 'required|array'
        ]);

        FaceEmbedding::updateOrCreate(
            [
                'mahasiswa_id' => $validated['mahasiswa_id']
            ],
            [
                'embedding' => $validated['embedding']
            ]
        );

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Data face embedding berhasil tersimpan'
            ]
        );
    }

    public function showEmbedding(Request $request)
    {
        $validated = $request->validate([
            'mahasiswa_id' => 'required'
        ]);

        $faceEmbedding = FaceEmbedding::where('mahasiswa_id', $validated['mahasiswa_id'])->first();

        if (!$faceEmbedding) {
            return response()->json([
                'status' => 'error',
                'message' => 'Embedding wajah tidak terdaftar',
                'data' => null
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Embedding wajah ditemukan',
            'data' => [
                'mahasiswa_id' => $faceEmbedding->mahasiswa_id,
                'embedding' => $faceEmbedding->embedding,
            ]
        ]);
    }
}
