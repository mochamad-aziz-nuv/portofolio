<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\FeedingLogResource;
use App\Models\Cycle;
use App\Models\FeedingLog;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedingLogController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $query = FeedingLog::with(['cycle.pond', 'recorder']);

        if ($request->filled('cycle_id')) {
            $query->where('cycle_id', $request->cycle_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('feeding_date', $request->date);
        }

        if ($request->filled('appetite_rate')) {
            $query->where('appetite_rate', $request->appetite_rate);
        }

        $perPage = min((int) ($request->per_page ?? 20), 100);
        $logs = $query->orderBy('feeding_date', 'desc')
                      ->orderBy('feeding_time', 'desc')
                      ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar log pakan berhasil diambil',
            'data' => FeedingLogResource::collection($logs->items()),
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
            'cycle_id' => 'required|exists:cycles,id',
            'feed_brand' => 'required|string|max:100',
            'feeding_date' => 'required|date',
            'feeding_time' => 'required|string',
            'amount_kg' => 'required|numeric|min:0.1',
            'doc' => 'nullable|integer|min:1',
            'appetite_rate' => 'required|in:optimal,normal,low,remaining_feed',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi catatan pakan gagal', $validator->errors()->toArray(), 422);
        }

        $data = $validator->validated();
        $cycle = Cycle::findOrFail($data['cycle_id']);

        if (empty($data['doc'])) {
            $data['doc'] = $cycle->current_doc;
        }

        $data['recorded_by'] = $request->user()?->id;

        $log = FeedingLog::create($data);
        $log->load(['cycle.pond', 'recorder']);

        return $this->sendResponse(
            new FeedingLogResource($log),
            'Catatan pemberian pakan berhasil disimpan',
            201
        );
    }

    public function summary(Request $request): JsonResponse
    {
        $days = (int) ($request->days ?? 7);
        $cycleId = $request->cycle_id;

        $query = FeedingLog::whereDate('feeding_date', '>=', now()->subDays($days));
        if ($cycleId) {
            $query->where('cycle_id', $cycleId);
        }

        $totalKg = (float) $query->sum('amount_kg');
        $totalSessions = $query->count();

        $dailyStats = $query->selectRaw('feeding_date, SUM(amount_kg) as total_kg, count(*) as count')
            ->groupBy('feeding_date')
            ->orderBy('feeding_date', 'asc')
            ->get();

        return $this->sendResponse([
            'days' => $days,
            'total_feed_kg' => round($totalKg, 2),
            'total_sessions' => $totalSessions,
            'daily_breakdown' => $dailyStats,
        ], 'Ringkasan konsumsi pakan berhasil diambil');
    }
}
