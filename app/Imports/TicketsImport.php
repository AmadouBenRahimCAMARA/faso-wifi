<?php

namespace App\Imports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class TicketsImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user_id = Auth::user()->id;
        // Check if row is empty or invalid if needed, but for now just map
        if(!isset($row[1])) return null; 

        return new Ticket([
            'user'     => $row[1],
           'password'    => $row[2],
           'dure' => $row[5],
           'slug' => Str::slug(Str::random(10)),
           'tarif_id' => Session::get("tarif_id"),
           'user_id' => $user_id,
        ]);
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
