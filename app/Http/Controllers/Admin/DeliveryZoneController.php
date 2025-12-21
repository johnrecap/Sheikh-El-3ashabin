<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\DeliveryZone;
use App\Services\DeliveryZoneService;
use App\Http\Requests\DeliveryZoneRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\DeliveryZoneResource;

class DeliveryZoneController extends AdminController
{
    public DeliveryZoneService $deliveryZoneService;

    public function __construct(DeliveryZoneService $deliveryZoneService)
    {
        parent::__construct();
        $this->deliveryZoneService = $deliveryZoneService;
        $this->middleware(['permission:settings'])->only('store', 'show', 'update', 'destroy');
    }

    public function index(
        PaginateRequest $request
    ): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return DeliveryZoneResource::collection($this->deliveryZoneService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function show(
        DeliveryZone $deliveryZone
    ): DeliveryZoneResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new DeliveryZoneResource($this->deliveryZoneService->show($deliveryZone));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(
        DeliveryZoneRequest $request
    ): DeliveryZoneResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new DeliveryZoneResource($this->deliveryZoneService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function update(
        DeliveryZoneRequest $request,
        DeliveryZone $deliveryZone
    ): DeliveryZoneResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new DeliveryZoneResource($this->deliveryZoneService->update($request, $deliveryZone));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(
        DeliveryZone $deliveryZone
    ): \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $this->deliveryZoneService->destroy($deliveryZone);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}