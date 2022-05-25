<?php

namespace App\Models;

use App\Models\Callback;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) \Ramsey\Uuid\Uuid::uuid4();
        });
    }

    public function callbacks(): HasMany
    {
        return $this->hasMany(Callback::class);
    }
}
