<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Client\Room;

use App\Http\Requests\Api\ApiRequest;
use App\Models\Room\Booking;
use Illuminate\Validation\Rule;

/**
 * @property string $from
 * @property string $to
 * @property string $payment_method
 * @property int $client_voucher_id
 * @property int $company_id
 */
class OrderRequest extends ApiRequest
{
    private array $rules = [];

    public function rules(): array
    {
        return $this->rules;
    }

    public function prepareForValidation(): void
    {
        $rules = [
            'from' => [
                'required',
                'date_format:Y-m-d H:i',
                'before:to',
            ],
            'to' => [
                'required',
                'date_format:Y-m-d H:i',
                'after:from',
            ],
            'payment_method' => [
                'required',
                'string',
                Rule::in(Booking::PAYMENT_METHOD_MAP),
            ],
        ];

        $this->addSpecificPaymentParams($rules);

        $this->rules = $rules;
    }

    private function addSpecificPaymentParams(array &$rules): void
    {
        switch ($this->payment_method) {
            case Booking::PAYMENT_VOUCHER:
                $rules['client_voucher_id'] = [
                    'required',
                    'integer',
                    'exists:client_vouchers,id',
                ];
                break;
            case Booking::PAYMENT_PASS:
                $rules['company_id'] = [
                    'required',
                    'integer',
                    'exists:companies,id',
                ];
                break;
        }
    }
}
