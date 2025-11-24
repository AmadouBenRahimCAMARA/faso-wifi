<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retrait extends Model
{
    use HasFactory;

    protected $fillable = [
        "montant",
        "transaction_id",
        "numero_paiement",
        "moyen_de_paiement",
        "slug",
        "statut",
        "user_id",
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
