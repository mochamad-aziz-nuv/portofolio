<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CycleResource;
use App\Models\Cycle;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CycleController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $query = Cycle::with('pond');

        if ($request->filled('pond_id')) {
            $query->where('pond_id', $request->pond_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cycles = $query->orderBy('start_date', 'desc')->get();

        return $this->sendResponse(
            CycleResource::collection($cycles),
            'Daftar siklus budidaya berhasil diambil'
        );
    }

    public function show(string $id): JsonResponse
    {
        $cycle = Cycle::with(['pond', 'feedingLogs' => function ($q) {
            $q->latest()->limit(20);
        }, 'waterQualityLogs' => function ($q) {
            $q->latest('recorded_at')->limit(10);
        }])->find($id);

        if (!$cycle) {
            return $this->sendError('Siklus tidak ditemukan', [], 404);
        }

        return $this->sendResponse(
            new CycleResource($cycle),
            'Detail siklus budidaya berhasil diambil'
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'pond_id' => 'required|exists:ponds,id',
            'cycle_code' => 'required|string|unique:cycles,cycle_code|max:50',
            'species' => 'required|string|max:100',
            'start_date' => 'required|date',
            'target_harvest_date' => 'nullable|date|after_or_equal:start_date',
            'initial_population' => 'required|integer|min:1',
            'target_fcr' => 'nullable|numeric|min:0.5|max:3.0',
            'target_size' => 'nullable|integer|min:1',
            'status' => 'required|in:active,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi siklus gagal', $validator->errors()->toArray(), 422);
        }

        $cycle = Cycle::create($validator->validated());

        return $this->sendResponse(
            new CycleResource($cycle),
            'Siklus baru berhasil dibuat',
            201
        );
    }

    public function summary(string $id): JsonResponse
    {
        $cycle = Cycle::with('pond')->find($id);

        if (!$cycle) {
            return $this->sendError('Siklus tidak ditemukan', [], 404);
        }

        $totalFeed = $cycle->feedingLogs()->sum('amount_kg');
        $logCount = $cycle->feedingLogs()->count();
        $latestFeed = $cycle->feedingLogs()->latest('feeding_date')->first();

        $avgDo = $cycle->waterQualityLogs()->avg('dissolved_oxygen');
        $avgPh = $cycle->waterQualityLogs()->avg('ph');
        $avgTemp = $cycle->waterQualityLogs()->avg('temperature');

        return $this->sendResponse([
            'cycle' => new CycleResource($cycle),
            'analytics' => [
                'current_doc' => $cycle->current_doc,
                'total_feed_consumed_kg' => round($totalFeed, 2),
                'total_feeding_sessions' => $logCount,
                'latest_feeding_date' => $latestFeed?->feeding_date?->format('Y-m-d'),
                'avg_water_metrics' => [
                    'avg_dissolved_oxygen' => round($avgDo ?? 0, 2),
                    'avg_ph' => round($avgPh ?? 0, 2),
                    'avg_temperature' => round($avgTemp ?? 0, 2),
                ],
            ]
        ], 'Ringkasan analitik siklus budidaya berhasil diambil');
    }
}

