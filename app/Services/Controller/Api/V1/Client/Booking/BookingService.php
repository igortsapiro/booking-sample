<?php

declare(strict_types=1);

namespace App\Services\Controller\Api\V1\Client\Booking;

use App\Exceptions\BadRequestHttpException;
use App\Http\Requests\Api\V1\Client\Room\OrderRequest;
use App\Models\Client\ClientVoucher;
use App\Models\Room\Booking;
use App\Models\Room;
use App\Models\ErrorMessage;
use App\Services\Controller\Api\Base\Client\RoomService as ApiGeneralRoomService;
use App\Services\General\BaseBookingService;
use App\Services\General\Validation\BookingValidationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class BookingService
{
    public function __construct(
        private readonly BaseBookingService $generalBookingService,
        private readonly BookingValidationService $bookingValidationService,
        private readonly ApiGeneralRoomService $apiGeneralRoomService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function delete(Booking $booking): JsonResponse
    {
        //delete functional

        return response()->json(['deleted']);
    }

    /**
     * @throws Exception
     */
    public function updatePartially(UpdatePartiallyRequest $request, Booking $booking): JsonResponse
    {
        //partial update functional

        return response()->json(['partial update']);
    }

    /**
     * @throws Exception
     */
    public function order(OrderRequest $request, Room $room): JsonResponse
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);
        $this->bookingValidationService->validateBeforeBook($from, $to);

        $paymentMethod = $request->payment_method;
        $clientVoucherId = $this->apiGeneralRoomService->getClientVoucherId(
            $paymentMethod,
            $request->client_voucher_id,
            $request->from,
            $request->to,
            $request->company_id
        );

        $booking = $this->createPendingBooking($room, $from, $to, $paymentMethod, $clientVoucherId);

        return response()->json([
            'booking' => $booking,
        ]);
    }

    /**
     * @throws Exception
     */
    public function createPendingBooking(
        Room $room,
        Carbon $from,
        Carbon $to,
        string $paymentMethod,
        ?int $clientVoucherId = null
    ): Booking {
        if (!in_array($paymentMethod, Booking::PAYMENT_METHOD_MAP)) {
            throw new BadRequestHttpException(ErrorMessage::getMessage('invalid_payment_method'));
        }

        $this->generalBookingService->checkBooking($from, $to, $room);

        /** @var User $authedUser */
        $authedUser = auth('api')->user();
        $client = $authedUser->typeable;

        /** @var Booking $booking */
        $booking = Booking::make([
            'start_time' => $from,
            'end_time' => $to,
            'status' => Booking::STATUS_PENDING,
            'payment_method' => $paymentMethod,
        ]);

        $attacheVoucherFlag = false;
        if ($clientVoucherId && $paymentMethod !== Booking::PAYMENT_CARD) {
            $clientVoucher = ClientVoucher::find($clientVoucherId);
            $this->bookingValidationService->validateBookingVoucher($clientVoucher);
            $attacheVoucherFlag = true;
        }

        $booking->client()->associate($client);
        $booking->room()->associate($room);
        $booking->save();

        if ($attacheVoucherFlag) {
            $booking->clientVouchers()->attach($clientVoucherId);
        }

        return $booking;
    }

    /**
     * @throws Exception
     */
    public function approvePendingBooking(ApproveRequest $request, Booking $booking): JsonResponse
    {
        //approve booking functional

        return response()->json(['approved']);
    }
}
