<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Room\Scopes\OnlyValidScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    use HasBookings;
    use HasUtcTimeAttributes;

    protected $casts = [
        'activated_at' => 'datetime',
        'opening_hours' => 'json',
        'is_active' => 'boolean',
        'is_private' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new OnlyValidScope());
    }
}
