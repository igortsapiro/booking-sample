<?php

declare(strict_types=1);

namespace App\Models\Room\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Carbon;

class OnlyValidScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $currentTime = Carbon::now();

        $builder->where('activated_at', '<=', $currentTime);
    }
}
