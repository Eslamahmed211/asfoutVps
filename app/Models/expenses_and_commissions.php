<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expenses_and_commissions extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id", "message", "commission",
        "type"
    ];

    protected $table = "expenses_and_commissions";


    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }


}
