<?php

namespace App\Services;


use Exception;
use App\Enums\Status;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Http\Requests\DeliveryZoneRequest;

class DeliveryZoneService
{
    protected array $deliveryZoneFilter = [
        'name',
        'email',
        'phone',
        'latitude',
        'longitude',
        'delivery_radius_kilometer',
        'delivery_charge_per_kilo',
        'minimum_order_amount',
        'address',
        'status',
        "except"
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return DeliveryZone::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->deliveryZoneFilter)) {

                        if ($key == "except") {
                            $explodes = explode('|', $request);
                            if (count($explodes)) {
                                foreach ($explodes as $explode) {
                                    $query->where('id', '!=', $explode);
                                }
                            }
                        } else {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(DeliveryZoneRequest $request)
    {
        try {
            return DeliveryZone::create($request->validated());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(DeliveryZoneRequest $request, DeliveryZone $deliveryZone)
    {
        try {
            return tap($deliveryZone)->update($request->validated());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(DeliveryZone $deliveryZone): void
    {
        try {
            $deliveryZone->delete();
        } catch (Exception $exception) {
            Log::info(QueryExceptionLibrary::message($exception));
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(DeliveryZone $deliveryZone): DeliveryZone
    {
        try {
            return $deliveryZone;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }


    /**
     * @throws Exception
     */
    public function deliveryZoneCheck(Request $request)
    {
        try {
            // New: Check by governorate directly (for guest checkout)
            if ($request->governorate) {
                $deliveryZone = DeliveryZone::where('governorate_name', $request->governorate)
                    ->where('status', Status::ACTIVE)
                    ->first();

                if ($deliveryZone) {
                    return $deliveryZone;
                }

                throw new Exception(trans('all.message.out_of_delivery_zone'), 422);
            }

            // Existing Logic: Check by Address ID -> Governorate
            // Check for 'id' (from frontend query param) OR 'address_id'
            $addressId = $request->address_id ?? $request->id;

            if ($addressId) {
                $address = \App\Models\Address::find($addressId);
                if ($address && $address->governorate) {
                    $deliveryZone = DeliveryZone::where('governorate_name', $address->governorate)
                        ->where('status', Status::ACTIVE)
                        ->first();

                    if ($deliveryZone) {
                        return $deliveryZone;
                    }
                }
            }

            // Fallback: If no address ID or gov match, try finding a 'General' zone or keep old logic if needed
            // For now, let's keep the old behavior as a fallback if no specific gov zone found
            // Return first active delivery zone (distance check disabled)
            $deliveryZone = DeliveryZone::where('status', Status::ACTIVE)->whereNull('governorate_name')->first();

            if ($deliveryZone) {
                return $deliveryZone;
            }

            // If still nothing, just return any active one (legacy behavior)
            $deliveryZone = DeliveryZone::where('status', Status::ACTIVE)->first();

            if ($deliveryZone) {
                return $deliveryZone;
            }

            throw new Exception(trans('all.message.out_of_delivery_zone'), 422);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function distanceCalculation($lat1, $lon1, $lat2, $lon2)
    {
        $radiationLat1 = deg2rad($lat1);
        $radiationLat2 = deg2rad($lat2);
        $theta = $lon1 - $lon2;
        $radiationTheta = deg2rad($theta);

        $distance = sin($radiationLat1) * sin($radiationLat2) + cos($radiationLat1) * cos($radiationLat2) * cos($radiationTheta);
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;
        $distance = $distance * 1.609344;

        return $distance;
    }
}
