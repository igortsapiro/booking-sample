<?php

declare(strict_types=1);

namespace App\Models\Client;

use App\Models\Client\Traits\HasDifferentVoucherTypes;
use App\Models\Client\Traits\HasVoucherTypeFilters;
use App\Models\Invoice\Invoice;
use App\Models\Type\Client;
use App\Models\Type\Company;
use App\Models\Type\Landlord;
use App\Traits\HasAvailableHours;
use App\Traits\HasHours;
use App\Traits\HasTransactions;
use App\Traits\HasValidCheck;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientVoucher extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];

    public function scopeOnlyPublicPass($query)
    {
        return $query->where('is_private', false)
            ->whereNotNull('invoice_id')
            ->where('sum', '<=', 0);
    }
}
