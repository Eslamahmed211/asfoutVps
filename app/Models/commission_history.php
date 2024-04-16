<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commission_history extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'user_id', 'commission'
    ];

    public function order()
    {
        return $this->belongsTo(order::class, "order_id")->with("details");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function scopeAuthAndModerators($query)
    {
        return $query->where(function ($q) {
            $q->where('user_id', auth()->id())
                ->orWhereIn('user_id', auth()->user()->moderators->modelKeys());
        });
    }
}
