<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\AppointmentCollection;

use App\Models\Appointments;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentsController extends Controller
{
    public function __construct(protected AppointmentService $appointmentService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = $this->appointmentService->getAll();
        return response()->json(AppointmentResource::collection($appointments));
    }
    /**
     * Display a listing of the resource for the authenticated user.
     */
    public function userAppointments()
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $appointments = $this->appointmentService->getByUserId($user->id);
        if ($appointments->isEmpty()) {
            return response()->json(['message' => 'No appointments found for this user'], 404);
        }
        return response()->json(AppointmentResource::collection($appointments));
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointments $appointments)
    {
        $appointment = $this->appointmentService->findById($appointments->id);
        return response()->json(new AppointmentResource($appointment));
    }

}
