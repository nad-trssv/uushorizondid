<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $service) {}

    public function masters()
    {
        $data = $this->service->getMasters();
        return response()->json(UserResource::collection($data), 200);
    }
}
