<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class moderatorOption extends Model
{
    protected $fillable = ["moderator_id" , "commissionType" , "commission"];

    public function moderator()
    {
        return $this->belongsTo(User::class, "moderator_id");
    }
}
