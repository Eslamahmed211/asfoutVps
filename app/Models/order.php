<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', "reasons", "Waitingdate", "bosta_delivery_price", "bosta_return_price", "postMan_id", 'logs', 'take', 'get',  'bosta_id', 'reference', 'clientName', 'clientPhone', 'clientPhone2', 'city', 'address', 'page', 'notes', 'notesBosta', 'status', 'delivery_price', 'return_price', 'trackingNumber', 'delivery_at' ,'company' , 'type'];


    protected $casts = [
        'logs' => 'array',
        'delivery_at' => "date",

    ];


    public function user()
    {
        return $this->belongsTo(user::class, 'user_id');
    }

    public function postMan()
    {
        return $this->belongsTo(user::class, 'postMan_id');
    }

    public function details()
    {
        return $this->hasMany(order_detail::class, 'order_id');
    }

    public function scopeForUserAndModerators($query)
    {
        return $query->orWhereIn('user_id', auth()->user()->moderators->modelKeys());
    }

    public function scopeTraderOrders($query)
    {
        return $query->whereHas("details.product", function ($q) {
            $q->where("trader_id", auth()->id());
        });
    }

    public function scopeCountByStatus($query, $status)
    {
        return $query->where("status", $status)->select("reference")->TraderOrders()->count();
    }

    public function scopeTraderOrdersDetails($query)
    {
        return $query->with(["details" => function ($q) {
            $q->whereHas("product", function ($q) {
                $q->where("trader_id", auth()->id());
            });
        }]);
    }



    public function scopePostManOrdersCount($query)
    {
        return $query->where("postMan_id", auth()->id())->count();
    }

    public function scopePostManOrders($query)
    {
        return $query->where("postMan_id", auth()->id())->orderBy("id", "desc")->simplepaginate(25);
    }


    public function scopeAuthAndModerators($query)
    {
        return $query->where(function ($q) {
            $q->where('user_id', auth()->id())
                ->orWhereIn('user_id', auth()->user()->moderators->modelKeys());
        });
    }

    // public function notes()
    // {
    //     return $this->hasMany(orderNotes::class, 'order_id')->orderBy("id", "desc");
    // }

    // function bosta()
    // {
    //     return $this->belongsTo(bostaOrder::class, "order_id");
    // }

    public function scopeCountByStatusIn($query, $status)
    {
        return $query->whereIn("status", $status)->select("id")->count();
    }

    public function scopeIdAndModerators($query, $Id)
    {
        return $query->where(function ($q) use ($Id) {
            $q->where("user_id", $Id)
                ->orWhereIn("user_id", User::find($Id)->moderators->modelKeys());
        });
    }

    public function scopeTraderOrdersDetailsById($query, $id)
    {
        return $query->with(["details" => function ($q)  use ($id) {
            $q->whereHas("product", function ($q) use ($id) {
                $q->where("trader_id", $id);
            });
        }]);
    }
}
