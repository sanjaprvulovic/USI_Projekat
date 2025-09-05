<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\DegustacioniPaket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\DegustacioniPaketController
 */
final class DegustacioniPaketControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $degustacioniPakets = DegustacioniPaket::factory()->count(3)->create();

        $response = $this->get(route('degustacioni-pakets.index'));

        $response->assertOk();
        $response->assertViewIs('degustacioniPaket.index');
        $response->assertViewHas('degustacioniPakets');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('degustacioni-pakets.create'));

        $response->assertOk();
        $response->assertViewIs('degustacioniPaket.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\DegustacioniPaketController::class,
            'store',
            \App\Http\Requests\DegustacioniPaketStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $NazivPaketa = fake()->word();
        $Cena = fake()->numberBetween(-10000, 10000);
        $Opis = fake()->text();

        $response = $this->post(route('degustacioni-pakets.store'), [
            'NazivPaketa' => $NazivPaketa,
            'Cena' => $Cena,
            'Opis' => $Opis,
        ]);

        $degustacioniPakets = DegustacioniPaket::query()
            ->where('NazivPaketa', $NazivPaketa)
            ->where('Cena', $Cena)
            ->where('Opis', $Opis)
            ->get();
        $this->assertCount(1, $degustacioniPakets);
        $degustacioniPaket = $degustacioniPakets->first();

        $response->assertRedirect(route('degustacioniPakets.index'));
        $response->assertSessionHas('degustacioniPaket.id', $degustacioniPaket->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $degustacioniPaket = DegustacioniPaket::factory()->create();

        $response = $this->get(route('degustacioni-pakets.show', $degustacioniPaket));

        $response->assertOk();
        $response->assertViewIs('degustacioniPaket.show');
        $response->assertViewHas('degustacioniPaket');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $degustacioniPaket = DegustacioniPaket::factory()->create();

        $response = $this->get(route('degustacioni-pakets.edit', $degustacioniPaket));

        $response->assertOk();
        $response->assertViewIs('degustacioniPaket.edit');
        $response->assertViewHas('degustacioniPaket');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\DegustacioniPaketController::class,
            'update',
            \App\Http\Requests\DegustacioniPaketUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $degustacioniPaket = DegustacioniPaket::factory()->create();
        $NazivPaketa = fake()->word();
        $Cena = fake()->numberBetween(-10000, 10000);
        $Opis = fake()->text();

        $response = $this->put(route('degustacioni-pakets.update', $degustacioniPaket), [
            'NazivPaketa' => $NazivPaketa,
            'Cena' => $Cena,
            'Opis' => $Opis,
        ]);

        $degustacioniPaket->refresh();

        $response->assertRedirect(route('degustacioniPakets.index'));
        $response->assertSessionHas('degustacioniPaket.id', $degustacioniPaket->id);

        $this->assertEquals($NazivPaketa, $degustacioniPaket->NazivPaketa);
        $this->assertEquals($Cena, $degustacioniPaket->Cena);
        $this->assertEquals($Opis, $degustacioniPaket->Opis);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $degustacioniPaket = DegustacioniPaket::factory()->create();

        $response = $this->delete(route('degustacioni-pakets.destroy', $degustacioniPaket));

        $response->assertRedirect(route('degustacioniPakets.index'));

        $this->assertModelMissing($degustacioniPaket);
    }
}
