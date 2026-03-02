<?php

namespace App\Imports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TicketsImport implements ToModel
{
    private $isFirstRow = true;
    private $isNewFormat = false;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user_id = Auth::user()->id;
        
        // Handle case where CSV delimiter is comma but Excel parser puts everything in row[0]
        if (count($row) === 1 && str_contains($row[0], ',')) {
            $row = str_getcsv($row[0], ',');
        }

        // Check if row is empty or invalid
        if(!isset($row[0]) || trim($row[0]) === '') return null; 

        if ($this->isFirstRow) {
            $this->isFirstRow = false;
            
            // Check if this is the new Mikrotik format header row
            // Format: id,username,password,comment,expiration,locked,profiles
            if (
                isset($row[1]) && isset($row[2]) &&
                strtolower(trim($row[0])) === 'id' &&
                strtolower(trim($row[1])) === 'username' &&
                strtolower(trim($row[2])) === 'password'
            ) {
                $this->isNewFormat = true;
                return null; // Skip header row
            }
            // If it's the legacy format with headers, skip it too based on common known headers
            if (strtolower(trim($row[0])) === 'utilisateur' || strtolower(trim($row[0])) === 'username' || strtolower(trim($row[0])) === 'login') {
                 return null;
            }
        }

        if ($this->isNewFormat) {
            // New Mikrotik Format
            // id (0), username (1), password (2), comment (3), expiration (4), locked (5), profiles (6)
            $username = isset($row[1]) ? trim($row[1]) : null;
            $password = isset($row[2]) ? trim($row[2]) : null;
            $dure = isset($row[6]) ? trim($row[6]) : null; // Profile is stored as duration/forfait
        } else {
            // Legacy Format
            // username (0), password (1), duration (2)
            $username = isset($row[0]) ? trim($row[0]) : null;
            $password = isset($row[1]) ? trim($row[1]) : null;
            $dure = isset($row[2]) ? trim($row[2]) : null;
        }

        // Only create the tickt if at least username and password exist
        if (empty($username) || empty($password)) {
            return null;
        }

        return new Ticket([
            'user'     => $username,
            'password' => $password,
            'dure'     => $dure,
            'slug'     => Str::slug(Str::random(10)),
            'tarif_id' => Session::get("tarif_id"),
            'user_id'  => $user_id,
        ]);
    }
}
