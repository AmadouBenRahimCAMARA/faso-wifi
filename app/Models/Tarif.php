<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;

    protected $fillable = [
        "forfait",
        "montant",
        "slug",
        "description",
        "wifi_id",
        "user_id",
    ];

    public function wifi(){
        return $this->belongsTo(Wifi::class);
    }

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
