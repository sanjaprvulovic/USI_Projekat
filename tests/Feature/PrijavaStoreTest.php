<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\PrIjava;
use App\Models\Degustacija;
use App\Models\DegustacioniPaket;
use App\Models\StatusPrijava;

class PrijavaStoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Minimalna definicija gate-ova za ovaj test:
        Gate::define('client', fn ($user) => true);
        Gate::define('managerOrAdmin', fn ($user) => false);
        Gate::define('manager', fn ($user) => false);
    }

    /**
     * Pomoćna metoda: URL za POST ka store akciji.
     * Ako koristiš resource route, najverovatnije je 'prijava.store'.
     * Ako je drugačije, samo ovde promeni.
     */
    private function storeUrl(): string
    {
        return route('prIjavas.store'); // ✔ tačan naziv rute
    }

    /** @test */
    public function klijent_moze_uspesno_da_kreira_prijavu(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        
        $degustacija = Degustacija::factory()->create([
            'Kapacitet' => 50,
            'Datum'     => now()->addDays(7),
        ]);
        $paket       = DegustacioniPaket::factory()->create();         // tabela: degustacioni_pakets

        // Napomena: kontroler u store() NE proverava da li je paket vezan za degustaciju (to radi update()),
        // pa za success scenario nije potrebno dodeljivati pivot.

        $response = $this->post($this->storeUrl(), [
            'degustacija_id'        => $degustacija->id,
            'degustacioni_paket_id' => $paket->id,
        ]);

        $response->assertRedirect(); // back()
        $response->assertSessionHas('success', 'Prijava je poslata. Čeka odobrenje.');

        $this->assertDatabaseCount('pr_ijavas', 1);
        $this->assertDatabaseHas('pr_ijavas', [
            'user_id'               => $user->id,
            'degustacija_id'        => $degustacija->id,
            'degustacioni_paket_id' => $paket->id,
        ]);

        // Provera da je status postavljen na "Na čekanju"
        $cekId = StatusPrijava::where('Naziv', 'Na čekanju')->value('id');
        $this->assertNotNull($cekId, 'Status "Na čekanju" nije kreiran.');
        $this->assertEquals(
            $cekId,
            PrIjava::first()->status_prijava_id,
            'Nova prijava nije u statusu "Na čekanju".'
        );
    }

    /** @test */
    public function ne_dozvoljava_duplu_aktivnu_prijavu_za_istu_degustaciju(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $degustacija = Degustacija::factory()->create();
        $paket       = DegustacioniPaket::factory()->create();

        // Kreiramo već POSTOJEĆU "aktivnu" prijavu: status != Otkazana/Odbijena
        $cekId = StatusPrijava::firstOrCreate(['Naziv' => 'Na čekanju'])->id;

        PrIjava::factory()->create([
            'Datum'                 => now(),
            'status_prijava_id'     => $cekId,
            'degustacija_id'        => $degustacija->id,
            'user_id'               => $user->id,
            'degustacioni_paket_id' => $paket->id,
        ]);

        // Pokušamo da kreiramo JOŠ JEDNU za istog user-a i istu degustaciju
        $response = $this->post($this->storeUrl(), [
            'degustacija_id'        => $degustacija->id,
            'degustacioni_paket_id' => $paket->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Već imate aktivnu prijavu za ovu degustaciju.');

        // I dalje samo jedna prijava u bazi
        $this->assertDatabaseCount('pr_ijavas', 1);
    }
}