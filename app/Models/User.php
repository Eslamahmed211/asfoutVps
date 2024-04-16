<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        "active",

        'city',
        'state',
        'address',
        'role',
        'permissions',


        "marketer_id",
        "wallet",
        "notification_settings"


    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'Mymoderators'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'notification_settings' => 'array'
    ];

    // function logs(): HasMany
    // {
    //     return  $this->hasMany(UsersLog::class, 'user_id')->with('editer')->orderBy('id', 'desc');
    // }


    public function carts()
    {
        return $this->hasMany(cart::class, 'user_id')->orderBy("id", "desc");
    }

    public function orders()
    {
        return $this->hasMany(order::class, "user_id");
    }

    public function moderators()
    {
        return $this->hasMany(User::class, "marketer_id")->orderBy("id", "desc")->withTrashed();
    }
    public function Mymoderators()
    {
        return $this->hasMany(User::class, "marketer_id")->orderBy("id", "desc")->withTrashed();
    }


    public function moderatorOptions()
    {
        return $this->hasOne(moderatorOption::class, "moderator_id");
    }



    public function paymentMethods()
    {
        return $this->hasMany(paymentMethod::class, "user_id")->orderBy("id", "desc");
    }


    public function withdraw()
    {
        return $this->hasMany(withdraw::class, "user_id")->orderBy("id", "desc");
    }

    public function moderators_withdraws()
    {
        return $this->hasMany(moderatorsWithdraw::class, "user_id")->orderBy("id", "desc");
    }





    public function deliveryOrders()
    {
        return $this->hasMany(order::class, "postMan_id");
    }

    // public function commission_histories()
    // {
    //     return $this->hasMany(commission_history::class, "user_id")->orderBy("id", "desc");
    // }

}
