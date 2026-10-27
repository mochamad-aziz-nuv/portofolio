<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\WaterQualityLogResource;
use App\Models\Pond;
use App\Models\WaterQualityLog;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WaterQualityLogController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $query = WaterQualityLog::with(['pond', 'cycle', 'recorder']);

        if ($request->filled('pond_id')) {
            $query->where('pond_id', $request->pond_id);
        }

        if ($request->filled('cycle_id')) {
            $query->where('cycle_id', $request->cycle_id);
        }

        if ($request->filled('condition_status')) {
            $query->where('condition_status', $request->condition_status);
        }

        $perPage = min((int) ($request->per_page ?? 20), 100);
        $logs = $query->orderBy('recorded_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar catatan kualitas air berhasil diambil',
            'data' => WaterQualityLogResource::collection($logs->items()),
            'meta' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'pond_id' => 'required|exists:ponds,id',
            'cycle_id' => 'nullable|exists:cycles,id',
            'recorded_at' => 'nullable|date',
            'temperature' => 'required|numeric|between:15,45',
            'ph' => 'required|numeric|between:4,12',
            'dissolved_oxygen' => 'required|numeric|between:0,20',
            'salinity' => 'required|numeric|between:0,50',
            'turbidity' => 'nullable|numeric|between:0,100',
            'ammonia' => 'nullable|numeric|between:0,10',
            'alkalinity' => 'nullable|numeric|between:0,500',
            'condition_status' => 'nullable|in:optimal,warning,critical',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi kualitas air gagal', $validator->errors()->toArray(), 422);
        }

        $data = $validator->validated();

        if (empty($data['recorded_at'])) {
            $data['recorded_at'] = now();
        }

        // Auto evaluate condition_status jika tidak diset manual
        if (empty($data['condition_status'])) {
            $do = (float) $data['dissolved_oxygen'];
            $ph = (float) $data['ph'];
            $ammonia = (float) ($data['ammonia'] ?? 0);

            if ($do < 4.0 || $ph < 7.0 || $ph > 9.0 || $ammonia > 0.1) {
                $data['condition_status'] = 'critical';
            } elseif ($do < 5.0 || $ph < 7.5 || $ph > 8.5 || $ammonia > 0.05) {
                $data['condition_status'] = 'warning';
            } else {
                $data['condition_status'] = 'optimal';
            }
        }

        $pond = Pond::with('activeCycle')->findOrFail($data['pond_id']);
        if (empty($data['cycle_id']) && $pond->activeCycle) {
            $data['cycle_id'] = $pond->activeCycle->id;
        }

        $data['recorded_by'] = $request->user()?->id;

        $log = WaterQualityLog::create($data);
        $log->load(['pond', 'cycle', 'recorder']);

        return $this->sendResponse(
            new WaterQualityLogResource($log),
            'Catatan kualitas air berhasil disimpan',
            201
        );
    }

    public function latest(): JsonResponse
    {
        $ponds = Pond::with(['latestWaterQuality.recorder', 'activeCycle'])
            ->where('status', 'active')
            ->get();

        $result = $ponds->map(function ($pond) {
            return [
                'pond_id' => $pond->id,
                'pond_name' => $pond->name,
                'pond_code' => $pond->code,
                'active_cycle' => $pond->activeCycle ? [
                    'id' => $pond->activeCycle->id,
                    'cycle_code' => $pond->activeCycle->cycle_code,
                    'current_doc' => $pond->activeCycle->current_doc,
                ] : null,
                'latest_quality' => $pond->latestWaterQuality ? new WaterQualityLogResource($pond->latestWaterQuality) : null,
            ];
        });

        return $this->sendResponse(
            $result,
            'Data kualitas air mutakhir seluruh kolam aktif berhasil diambil'
        );
    }
}

