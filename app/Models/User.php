<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'pays',
        'phone',
        'email',
        'password',
        'is_admin',
        'status',
        'verification_code',
        'verification_expires_at',
    ];

    /**
     * Check if the user is an administrator.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * Check if the user is banned.
     *
     * @return bool
     */
    public function isBanned()
    {
        return $this->status === 'banned';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function wifis(){

        return $this->hasMany(Wifi::class);
    }

    public function tarifs(){

        return $this->hasMany(Tarif::class);
    }
    public function tickets(){

        return $this->hasMany(Ticket::class);
    }

    public function paiements(){

        return $this->hasMany(Paiement::class);
    }
    public function soldes(){

        return $this->hasMany(Solde::class);
    }

    public function retraits(){

        return $this->hasMany(Retrait::class);
    }

    /**
     * Calculate the real-time balance for the user.
     * For Admin: Total Gross Revenue.
     * For Reseller: (Total Net Gain) - (Total Paid Withdrawals).
     */
    public function calculateBalance()
    {
        if ($this->isAdmin()) {
            return \App\Models\Paiement::where('paiements.status', 'completed')
                ->join('tickets', 'paiements.ticket_id', '=', 'tickets.id')
                ->join('tarifs', 'tickets.tarif_id', '=', 'tarifs.id')
                ->sum(\Illuminate\Support\Facades\DB::raw('CAST(tarifs.montant AS DECIMAL)'));
        }

        // Reseller Logic: Align with Bilan View
        $paiements = \App\Models\Paiement::whereHas('ticket', function($q) {
            $q->where('user_id', $this->id);
        })->where('status', 'completed')->get();

        $chiffreAffairesTotal = 0;
        foreach($paiements as $p){
             // Load relationship if not loaded
             $chiffreAffairesTotal += (float)$p->ticket->tarif->montant;
        }

        // 10% Commission
        $netTotal = $chiffreAffairesTotal * 0.90;

        // Paid Withdrawals
        $totalRetraits = \App\Models\Retrait::where('user_id', $this->id)
            ->where('statut', 'PAYE')
            ->sum('montant');

        return $netTotal - $totalRetraits;
    }
}
