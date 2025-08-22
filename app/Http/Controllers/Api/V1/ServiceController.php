<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\ServiceShowResource;
use App\Services\ServiceService;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected ServiceService $service;

    public function __construct(ServiceService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {
            app()->setLocale($request->header('Accept-Language', 'en'));
            $locale = $request->get('locale', app()->getLocale());

            $filteredData = $this->service->listFiltered($locale, $request);
            $serviceStats = $this->service->getStat();

            $paginatedData = PaginateResource::make($filteredData, ServiceResource::class);

            return response()->json([
                'paginated' => $paginatedData,
                'stats' => $serviceStats,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching services.', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id, Request $request)
    {
        app()->setLocale($request->header('Accept-Language', 'en'));

        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);
        $data = $this->service->getById($id, $locale);
        return new ServiceShowResource($data);
    }

    public function store(ServiceRequest $request): JsonResponse
    {
        try {
            $data = $this->service->create($request->validated());
            return response()->json(new ServiceResource($data), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the service.', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(ServiceRequest $request, Service $service)
    {
        try {
            $data = $this->service->update($service, $request->validated());
            return $data->translations;
            return response()->json(new ServiceResource($data), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the service.', 'message' => $e->getMessage()], 500);
        }
    }

    public function toggleStatus(Service $service): JsonResponse
    {
        try {
            $service = $this->service->toggleStatus($service);
            return response()->json(new ServiceResource($service), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while toggling the service status.', 'message' => $e->getMessage()], 500);
        }
    }
    public function destroy(Service $service): JsonResponse
    {
        try {
            $this->service->delete($service);
            return response()->json(['message' => 'Service deleted successfully.'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the service.', 'message' => $e->getMessage()], 500);
        }
    }

    public function addMaster(Service $service, Request $request): JsonResponse
    {
        try {
            $this->service->addMaster($service, $request->get('master_id'));
            return response()->json([
                'success' => true,
                'message' => 'Master added successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while adding the master.', 'message' => $e->getMessage()], 500);
        }
    }

    public function removeMaster(Service $service, Request $request): JsonResponse
    {
        try {
            $masterId = $request->get('master_id');
            $this->service->removeMaster($service, $masterId);
            
            return response()->json([
                'success' => true,
                'message' => 'Master removed successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }
}
