<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\PrIjava;
use App\Models\Degustacija;
use App\Models\DegustacioniPaket;
use App\Models\StatusPrijava;

class PrijavaDestroyTest extends TestCase
{
    use RefreshDatabase;

    private function destroyUrl(PrIjava $prijava): string
    {
        return route('prIjave.destroy', $prijava); // DELETE /prijave/{prijava}
    }

    private function makeDegustacija(array $attrs = []): Degustacija
    {
        // dovoljno je da Datum bude >24h u budućnosti da ne blokira
        $defaults = [
            'Datum' => now()->addDays(3),
            // dodaj ovde druge kolone po potrebi (npr. 'Kapacitet' => 50)
        ];
        return Degustacija::factory()->create(array_merge($defaults, $attrs));
    }

    private function createActivePrijava(User $user, Degustacija $deg, DegustacioniPaket $paket, string $statusNaziv = 'Na čekanju'): PrIjava
    {
        $statusId = StatusPrijava::firstOrCreate(['Naziv' => $statusNaziv])->id;

        return PrIjava::factory()->create([
            'Datum'                 => now(),
            'status_prijava_id'     => $statusId,
            'degustacija_id'        => $deg->id,
            'user_id'               => $user->id,
            'degustacioni_paket_id' => $paket->id,
            'prisutan'              => false,
            'checked_in_at'         => null,
        ]);
    }

    /** @test */
    public function vlasnik_moze_da_otkaze_prijavu_kad_je_dozvoljeno(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $deg   = $this->makeDegustacija(); // Datum > 24h unapred
        $paket = DegustacioniPaket::factory()->create();

        $prijava = $this->createActivePrijava($user, $deg, $paket, 'Na čekanju');

        $response = $this->delete($this->destroyUrl($prijava));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Prijava je otkazana.');

        $otkazanaId = StatusPrijava::where('Naziv', 'Otkazana')->value('id');
        $this->assertNotNull($otkazanaId);

        $this->assertDatabaseHas('pr_ijavas', [
            'id'                 => $prijava->id,
            'status_prijava_id'  => $otkazanaId,
        ]);
    }

    /** @test */
    public function ne_vlasnik_dobija_403_i_prijava_ostaje_nepromenjena(): void
    {
        $owner  = User::factory()->create();
        $other  = User::factory()->create();
        $this->actingAs($other);

        $deg   = $this->makeDegustacija();
        $paket = DegustacioniPaket::factory()->create();

        $prijava = $this->createActivePrijava($owner, $deg, $paket, 'Na čekanju');

        $response = $this->delete($this->destroyUrl($prijava));
        $response->assertStatus(403);

        // status ostao "Na čekanju"
        $cekId = StatusPrijava::where('Naziv', 'Na čekanju')->value('id');
        $this->assertDatabaseHas('pr_ijavas', [
            'id'                 => $prijava->id,
            'status_prijava_id'  => $cekId,
        ]);
    }

    /** @test */
    public function ne_moze_otkazivanje_u_poslednja_24h_pre_pocetka(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $deg   = $this->makeDegustacija(['Datum' => now()->addHours(12)]); // < 24h
        $paket = DegustacioniPaket::factory()->create();

        $prijava = $this->createActivePrijava($user, $deg, $paket, 'Prihvaćena');

        $response = $this->delete($this->destroyUrl($prijava));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Prijavu nije moguće otkazati u poslednjih 24h pre početka.');

        $acceptedId = StatusPrijava::where('Naziv', 'Prihvaćena')->value('id');
        $this->assertDatabaseHas('pr_ijavas', [
            'id'                 => $prijava->id,
            'status_prijava_id'  => $acceptedId,
        ]);
    }

    /** @test */
    public function ne_moze_otkazati_vec_otkazanu_prijavu(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $deg   = $this->makeDegustacija();
        $paket = DegustacioniPaket::factory()->create();

        $otkazanaId = StatusPrijava::firstOrCreate(['Naziv' => 'Otkazana'])->id;

        $prijava = PrIjava::factory()->create([
            'Datum'                 => now(),
            'status_prijava_id'     => $otkazanaId,
            'degustacija_id'        => $deg->id,
            'user_id'               => $user->id,
            'degustacioni_paket_id' => $paket->id,
        ]);

        $response = $this->delete($this->destroyUrl($prijava));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Prijava je već otkazana.');

        $this->assertDatabaseHas('pr_ijavas', [
            'id'                 => $prijava->id,
            'status_prijava_id'  => $otkazanaId,
        ]);
    }
}
