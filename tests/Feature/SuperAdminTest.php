<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wifi;
use App\Models\Tarif;
use App\Models\Ticket;
use App\Models\Paiement;
use App\Models\Retrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class SuperAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_see_vendeur_in_wifi_list()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['nom' => 'Vendeur', 'prenom' => 'Test']);
        Wifi::create([
            'nom' => 'Wifi Test',
            'description' => 'Description',
            'slug' => Str::slug(Str::random(10)),
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($admin)->get(route('wifi.index'));

        $response->assertStatus(200);
        $response->assertSee('Vendeur');
        $response->assertSee('Vendeur Test');
    }

    public function test_admin_can_see_vendeur_in_tarif_list()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['nom' => 'Vendeur', 'prenom' => 'Test']);
        $wifi = Wifi::create([
            'nom' => 'Wifi Test',
            'description' => 'Description',
            'slug' => Str::slug(Str::random(10)),
            'user_id' => $user->id
        ]);
        Tarif::create([
            'forfait' => '1H',
            'montant' => '100',
            'slug' => Str::slug(Str::random(10)),
            'description' => 'Desc',
            'wifi_id' => $wifi->id,
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($admin)->get(route('tarifs.index'));

        $response->assertStatus(200);
        $response->assertSee('Vendeur');
        $response->assertSee('Vendeur Test');
    }

    public function test_admin_can_see_vendeur_in_ticket_list()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['nom' => 'Vendeur', 'prenom' => 'Test']);
        $wifi = Wifi::create([
            'nom' => 'Wifi Test',
            'description' => 'Description',
            'slug' => Str::slug(Str::random(10)),
            'user_id' => $user->id
        ]);
        $tarif = Tarif::create([
            'forfait' => '1H',
            'montant' => '100',
            'slug' => Str::slug(Str::random(10)),
            'description' => 'Desc',
            'wifi_id' => $wifi->id,
            'user_id' => $user->id
        ]);
        Ticket::create([
            'user' => 'user123',
            'password' => 'pass123',
            'dure' => '1H',
            'slug' => Str::slug(Str::random(10)),
            'etat_ticket' => 'EN_VENTE',
            'tarif_id' => $tarif->id,
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($admin)->get(route('ticket.index'));

        $response->assertStatus(200);
        $response->assertSee('Vendeur');
        $response->assertSee('Vendeur Test');
    }

    public function test_admin_can_see_vendeur_in_paiement_list()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['nom' => 'Vendeur', 'prenom' => 'Test']);
        $wifi = Wifi::create([
            'nom' => 'Wifi Test',
            'description' => 'Description',
            'slug' => Str::slug(Str::random(10)),
            'user_id' => $user->id
        ]);
        $tarif = Tarif::create([
            'forfait' => '1H',
            'montant' => '100',
            'slug' => Str::slug(Str::random(10)),
            'description' => 'Desc',
            'wifi_id' => $wifi->id,
            'user_id' => $user->id
        ]);
        $ticket = Ticket::create([
            'user' => 'user123',
            'password' => 'pass123',
            'dure' => '1H',
            'slug' => Str::slug(Str::random(10)),
            'etat_ticket' => 'EN_VENTE',
            'tarif_id' => $tarif->id,
            'user_id' => $user->id
        ]);
        Paiement::create([
            'transaction_id' => 'TRANS123',
            'numero' => '12345678',
            'slug' => Str::slug(Str::random(10)),
            'moyen_de_paiement' => 'OM',
            'ticket_id' => $ticket->id,
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($admin)->get(route('paiement.index'));

        $response->assertStatus(200);
        $response->assertSee('Vendeur');
        $response->assertSee('Vendeur Test');
    }

    public function test_admin_can_see_vendeur_in_retrait_list()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['nom' => 'Vendeur', 'prenom' => 'Test']);
        Retrait::create([
            'montant' => '5000',
            'transaction_id' => 'TRANS123',
            'numero_paiement' => '12345678',
            'moyen_de_paiement' => 'OM',
            'slug' => Str::slug(Str::random(10)),
            'statut' => 'EN_ATTENTE',
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($admin)->get(route('retrait.index'));

        $response->assertStatus(200);
        $response->assertSee('Vendeur');
        $response->assertSee('Vendeur Test');
    }
}
