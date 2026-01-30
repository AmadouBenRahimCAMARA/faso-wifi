<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        "transaction_id",
        "numero",
        "slug",
        "moyen_de_paiement",
        "ticket_id",
        "user_id",
        "status"
    ];

    public function ticket(){
        return $this->belongsTo(Ticket::class);
    }
}
