<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\DeliveryZoneService;
use App\Http\Resources\DeliveryZoneResource;

class DeliveryZoneController extends Controller
{
    private DeliveryZoneService $deliveryZoneService;

    public function __construct(DeliveryZoneService $deliveryZoneService)
    {
        $this->deliveryZoneService = $deliveryZoneService;
    }

    public function deliveryZone(Request $request): DeliveryZoneResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new DeliveryZoneResource($this->deliveryZoneService->deliveryZoneCheck($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
