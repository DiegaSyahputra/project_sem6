<?php

namespace App\Http\Controllers\Api\Location;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use function App\Services\calculateDistance;

class LocationController extends Controller
{
    public function validateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi_id' => 'required'
        ]);

        $lokasi = Lokasi::where('id', $request->lokasi_id)->first();

        if (!$lokasi) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Lokasi tidak tersedia'
                ]
            );
        }

        $distance = calculateDistance(
            $request->latitude,
            $request->longitude,
            $lokasi->latitude,
            $lokasi->longitude,
        );

        if ($distance > $lokasi->radius) {
            return response()->json([
                'status' => 'error',
                'message' => 'Di luar area presensi',
                'distance' => $distance
            ]);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Dalam area',
                'lokasi_id' => $lokasi->id
            ]
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:1',
        ]);

        $existing = Lokasi::whereRaw('LOWER(TRIM(nama)) = ?', [
            strtolower(trim($validated['nama']))
        ])->first();
        
        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lokasi dengan nama "' . $existing->nama . '" sudah terdaftar',
            ]);
        }

        $lokasi = Lokasi::create([
            'nama' => $validated['nama'],
            'longitude' => $validated['longitude'],
            'latitude' => $validated['latitude'],
            'radius' => $validated['radius'],
            'is_active' => 1,
        ]);

        $lokasi->radius = (int) $lokasi->radius;

        return response()->json([
            'status' => 'success',
            'message' => 'Lokasi berhasil ditambahkan',
            'data' => $lokasi
        ], 201);
    }

    public function index()
    {
        $lokasi = Lokasi::where('is_active', 1)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Lokasi berhasil diambil',
            'data' => $lokasi
        ]);
    }
}
