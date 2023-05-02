<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Client\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Client\Room\OrderRequest;
use App\Models\Room;
use App\Models\Room\Booking;
use App\Services\Controller\Api\V1\Client\Booking\BookingService;
use Exception;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function __construct(private readonly BookingService $bookingService)
    {
    }

    /**
     * @throws Exception
     */
    public function order(OrderRequest $request, Room $room): JsonResponse
    {
        return $this->bookingService->order($request, $room);
    }

    /**
     * @throws Exception
     */
    public function updatePartially(UpdatePartiallyRequest $request, Booking $booking): JsonResponse
    {
        return $this->bookingService->updatePartially($request, $booking);
    }

    /**
     * @throws Exception
     */
    public function delete(Booking $booking): JsonResponse
    {
        return $this->bookingService->delete($booking);
    }

    /**
     * @throws Exception
     */
    public function approve(ApproveRequest $request, Booking $booking): JsonResponse
    {
        return $this->bookingService->approvePendingBooking($request, $booking);
    }
}
