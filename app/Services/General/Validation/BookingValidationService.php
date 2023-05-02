<?php

declare(strict_types=1);

namespace App\Services\General\Validation;

use App\Exceptions\BadRequestHttpException;
use App\Models\Client\ClientVoucher;
use App\Models\ErrorMessage;
use App\Models\Room;
use App\Models\Room\Booking;
use Exception;
use Illuminate\Support\Carbon;

class BookingValidationService
{
    /**
     * @throws BadRequestHttpException
     */
    public function validateBookingVoucher(?ClientVoucher $clientVoucher): void
    {
        //some rules to validate voucher

        if (!$clientVoucher->isValid()) {
            throw new BadRequestHttpException(ErrorMessage::getMessage('client_voucher_is_not_available'));
        }
    }

    /**
     * @throws BadRequestHttpException
     */
    public function validateBookingAbility(Booking $booking)
    {
        /** @var Client $bookClient */
        $bookClient = $booking->client;

        /** @var User $bookUser */
        $bookUser = ($bookClient->parent) ? $bookClient->parent?->user : $bookClient?->user;
        $phoneVerifiedAt = $bookUser?->phone_verified_at;

        if (is_null($phoneVerifiedAt)) {
            throw new BadRequestHttpException(ErrorMessage::getMessage('client_not_verified_in_app'));
        }

        if (!$bookUser?->is_active) {
            throw new BadRequestHttpException(ErrorMessage::getMessage('client_blocked_in_app'));
        }
    }

    /**
     * @throws Exception
     */
    public function validateBeforeBook(
        Carbon $from,
        bool|Carbon $to
    ): void {
        //validation functional
    }

    /**
     * @throws BadRequestHttpException
     */
    public function validateAfterBook(
        Carbon $from,
        Carbon $to,
        Room $room,
        Booking $booking = null
    ): void {
        //validation functional
    }
}
