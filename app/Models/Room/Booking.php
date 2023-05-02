<?php

declare(strict_types=1);

namespace App\Models\Room;

use App\Models\Room;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Booking extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_CANCELED = 'canceled';

    public const STATUS_PENDING_REFUND = 'pending_refund';

    public const STATUS_PENDING_CONFIRM = 'pending_confirm';

    public const STATUS_REFUNDED = 'refunded';

    public const PENDING_STATUS_MAP = [
        self::STATUS_PENDING,
        self::STATUS_PENDING_CONFIRM,
    ];

    public const APPROVED_STATUS_MAP = [
        self::STATUS_APPROVED,
        self::STATUS_PENDING_REFUND,
    ];

    public const CANCELED_STATUS_MAP = [
        self::STATUS_CANCELED,
        self::STATUS_REFUNDED,
    ];

    public const PAYMENT_CARD = 'card';

    public const PAYMENT_VOUCHER = 'voucher';

    public const PAYMENT_PASS = 'pass';

    public const INNER_PAYMENT_METHOD_MAP = [
        self::PAYMENT_VOUCHER,
        self::PAYMENT_PASS,
    ];

    public const PAYMENT_METHOD_MAP = [
        self::PAYMENT_CARD,
        self::PAYMENT_VOUCHER,
        self::PAYMENT_PASS,
    ];

    protected $fillable = [
        'start_time',
        'end_time',
        'payment_method',
        'status',
        'minutes',
        'sum',
        'actual_cost',
        'linked_booking_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'reserved_till' => 'datetime',
        'actual_cost' => 'double',
    ];

    protected $appends = [
        'front_start_time',
        'front_end_time',
    ];

    protected $with = [
        'clientVouchers',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function clientVouchers(): MorphToMany
    {
        return $this->morphedByMany(ClientVoucher::class, 'bookable')->orderByDesc('bookables.id');
    }

    public function validClientVouchers(): MorphToMany
    {
        return $this
            ->clientVouchers()
            ->where(
                fn (EloquentBuilder $query) => $query
                ->filterOnlyValidVouchers()
                ->orWhere(
                    fn (EloquentBuilder $subQuery) => $subQuery
                    ->filterOnlyPrivateVouchers()
                )
            );
    }

    protected function frontStartTime(): Attribute
    {
        $frontStartTime = $this->start_time?->format('Y-m-d H:i');

        return Attribute::make(get: fn () => $frontStartTime);
    }

    protected function frontEndTime(): Attribute
    {
        $frontEndTime = $this->end_time?->format('Y-m-d H:i');

        return Attribute::make(get: fn () => $frontEndTime);
    }
}
