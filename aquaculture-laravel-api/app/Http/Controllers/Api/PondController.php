<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PondResource;
use App\Models\Pond;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PondController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $query = Pond::with(['activeCycle', 'latestWaterQuality']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('pond_type')) {
            $query->where('pond_type', $request->pond_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $ponds = $query->orderBy('name')->get();

        return $this->sendResponse(
            PondResource::collection($ponds),
            'Daftar kolam berhasil diambil'
        );
    }

    public function show(string $id): JsonResponse
    {
        $pond = Pond::with(['activeCycle.feedingLogs' => function ($q) {
            $q->latest()->limit(5);
        }, 'latestWaterQuality', 'cycles'])->find($id);

        if (!$pond) {
            return $this->sendError('Data kolam tidak ditemukan', [], 404);
        }

        return $this->sendResponse(
            new PondResource($pond),
            'Detail kolam berhasil diambil'
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:ponds,code',
            'pond_type' => 'required|in:hdpe,concrete,earthen',
            'length_m' => 'nullable|numeric|min:0',
            'width_m' => 'nullable|numeric|min:0',
            'depth_m' => 'required|numeric|min:0.5',
            'area_sqm' => 'required|numeric|min:1',
            'volume_m3' => 'nullable|numeric|min:1',
            'status' => 'required|in:active,preparation,maintenance,harvested',
            'location' => 'nullable|string|max:150',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi data kolam gagal', $validator->errors()->toArray(), 422);
        }

        $data = $validator->validated();
        if (empty($data['volume_m3']) && !empty($data['area_sqm']) && !empty($data['depth_m'])) {
            $data['volume_m3'] = round($data['area_sqm'] * $data['depth_m'], 2);
        }

        $pond = Pond::create($data);

        return $this->sendResponse(
            new PondResource($pond),
            'Kolam baru berhasil ditambahkan',
            201
        );
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $pond = Pond::find($id);

        if (!$pond) {
            return $this->sendError('Kolam tidak ditemukan', [], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:100',
            'code' => 'sometimes|required|string|max:20|unique:ponds,code,' . $pond->id,
            'pond_type' => 'sometimes|required|in:hdpe,concrete,earthen',
            'length_m' => 'nullable|numeric|min:0',
            'width_m' => 'nullable|numeric|min:0',
            'depth_m' => 'sometimes|required|numeric|min:0.5',
            'area_sqm' => 'sometimes|required|numeric|min:1',
            'volume_m3' => 'nullable|numeric|min:1',
            'status' => 'sometimes|required|in:active,preparation,maintenance,harvested',
            'location' => 'nullable|string|max:150',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi pembaruan gagal', $validator->errors()->toArray(), 422);
        }

        $pond->update($validator->validated());

        return $this->sendResponse(
            new PondResource($pond),
            'Data kolam berhasil diperbarui'
        );
    }

    public function destroy(string $id): JsonResponse
    {
        $pond = Pond::find($id);

        if (!$pond) {
            return $this->sendError('Kolam tidak ditemukan', [], 404);
        }

        $pond->delete();

        return $this->sendResponse(null, 'Kolam berhasil dihapus');
    }
}
