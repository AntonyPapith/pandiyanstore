<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerDetail extends Model
{
    protected $fillable = ['user_id', 'city', 'area', 'nearby_landmark', 'address'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
