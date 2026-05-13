<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaceEmbedding extends Model
{

    protected $table = 'face_embedding';
    protected $fillable = [
        'mahasiswa_id',
        'embedding'
    ];

    protected $casts = [
        'embedding' => 'array'
    ];
}
