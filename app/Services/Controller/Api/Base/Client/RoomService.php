<?php

declare(strict_types=1);

namespace App\Services\Controller\Api\Base\Client;

use App\Exceptions\BadRequestHttpException;
use App\Models\ErrorMessage;
use Illuminate\Support\Carbon;

class RoomService
{
    /**
     * @throws BadRequestHttpException
     */
    public function getClientVoucherId(
        string $paymentMethodCode,
        null|string|int $clientVoucherId,
        string $bookingFrom,
        string $bookingTo,
        null|string|int $companyId
    ): ?int {
        $dateFrom = Carbon::parse($bookingFrom);
        $dateTo = Carbon::parse($bookingTo);
        $possibleDuration = $dateTo->diffInMinutes($dateFrom);
        $roomPass = $this->getRoomPassVoucher($possibleDuration, $companyId);

        if (is_null($roomPass)) {
            throw new BadRequestHttpException(ErrorMessage::getMessage('room_pass_not_available_for_company'));
        }

        return $roomPass->id;
    }

    /**
     * @throws BadRequestHttpException
     */
    private function getRoomPassVoucher(
        int $possibleDuration,
        null|string|int $companyId
    ): ?ClientVoucher {
        return ClientVoucher::query()->first()?->id;
    }
}
