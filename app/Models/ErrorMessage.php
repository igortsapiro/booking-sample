<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorMessage extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public static function getMessage(string $key, mixed $param = null): string
    {
        $message = self::where('key', '=', $key)->first()->message;

        return (is_null($param))
            ? $message
            : str_replace('{value}', strval($param), $message);
    }
}
