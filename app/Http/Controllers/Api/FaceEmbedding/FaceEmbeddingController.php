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
}
