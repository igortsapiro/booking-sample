<?php

declare(strict_types=1);

namespace App\Services\General;

use App\Models\Client\ClientVoucher;
use App\Models\Room;
use App\Models\Room\Booking;
use App\Services\General\Validation\BookingValidationService;
use Exception;
use Illuminate\Support\Carbon;

class BaseBookingService
{
    public function __construct(
        protected BookingValidationService $bookingValidationService
    ) {
    }

    /**
     * @throws Exception
     */
    public function checkBooking(
        Carbon $from,
        Carbon $to,
        Room $room,
        Booking $booking = null,
        ClientVoucher $clientVoucher = null
    ): void {
        $this->checkApprovedBooking($from, $to, $room, $booking, $clientVoucher);
        $this->checkPendingBooking($from, $to, $room, $booking);
    }

    /**
     * @throws Exception
     */
    private function checkApprovedBooking(
        Carbon $from,
        Carbon $to,
        Room $room,
        Booking $booking = null,
        ClientVoucher $clientVoucher = null
    ): void {
        $this->bookingValidationService->validateAfterBook($from, $to, $room, $booking);

        $this->checkIsRoomBooked($from, $to, $room, $booking);
        $this->checkIsEnoughMoney($from, $to, $booking, $clientVoucher);
    }

    /**
     * @throws Exception
     */
    public function checkPendingBooking(
        Carbon $from,
        Carbon $to,
        Room $room,
        Booking $booking = null
    ): void {
        $this->checkIsRoomBooked($from, $to, $room, $booking);
        $this->checkIsBookInExistedBookingTime($from, $to, $room, $booking);
    }

}
