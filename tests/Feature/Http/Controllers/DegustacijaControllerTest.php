<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Degustacija;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\DegustacijaController
 */
final class DegustacijaControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $degustacijas = Degustacija::factory()->count(3)->create();

        $response = $this->get(route('degustacijas.index'));

        $response->assertOk();
        $response->assertViewIs('degustacija.index');
        $response->assertViewHas('degustacijas');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('degustacijas.create'));

        $response->assertOk();
        $response->assertViewIs('degustacija.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\DegustacijaController::class,
            'store',
            \App\Http\Requests\DegustacijaStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $Naziv = fake()->word();
        $Datum = Carbon::parse(fake()->dateTime());
        $Lokacija = fake()->word();
        $Kapacitet = fake()->numberBetween(-10000, 10000);
        $user_id = fake()->numberBetween(-100000, 100000);
        $status_degustacija_id = fake()->numberBetween(-100000, 100000);

        $response = $this->post(route('degustacijas.store'), [
            'Naziv' => $Naziv,
            'Datum' => $Datum->toDateTimeString(),
            'Lokacija' => $Lokacija,
            'Kapacitet' => $Kapacitet,
            'user_id' => $user_id,
            'status_degustacija_id' => $status_degustacija_id,
        ]);

        $degustacijas = Degustacija::query()
            ->where('Naziv', $Naziv)
            ->where('Datum', $Datum)
            ->where('Lokacija', $Lokacija)
            ->where('Kapacitet', $Kapacitet)
            ->where('user_id', $user_id)
            ->where('status_degustacija_id', $status_degustacija_id)
            ->get();
        $this->assertCount(1, $degustacijas);
        $degustacija = $degustacijas->first();

        $response->assertRedirect(route('degustacijas.index'));
        $response->assertSessionHas('degustacija.id', $degustacija->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $degustacija = Degustacija::factory()->create();

        $response = $this->get(route('degustacijas.show', $degustacija));

        $response->assertOk();
        $response->assertViewIs('degustacija.show');
        $response->assertViewHas('degustacija');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $degustacija = Degustacija::factory()->create();

        $response = $this->get(route('degustacijas.edit', $degustacija));

        $response->assertOk();
        $response->assertViewIs('degustacija.edit');
        $response->assertViewHas('degustacija');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\DegustacijaController::class,
            'update',
            \App\Http\Requests\DegustacijaUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $degustacija = Degustacija::factory()->create();
        $Naziv = fake()->word();
        $Datum = Carbon::parse(fake()->dateTime());
        $Lokacija = fake()->word();
        $Kapacitet = fake()->numberBetween(-10000, 10000);
        $user_id = fake()->numberBetween(-100000, 100000);
        $status_degustacija_id = fake()->numberBetween(-100000, 100000);

        $response = $this->put(route('degustacijas.update', $degustacija), [
            'Naziv' => $Naziv,
            'Datum' => $Datum->toDateTimeString(),
            'Lokacija' => $Lokacija,
            'Kapacitet' => $Kapacitet,
            'user_id' => $user_id,
            'status_degustacija_id' => $status_degustacija_id,
        ]);

        $degustacija->refresh();

        $response->assertRedirect(route('degustacijas.index'));
        $response->assertSessionHas('degustacija.id', $degustacija->id);

        $this->assertEquals($Naziv, $degustacija->Naziv);
        $this->assertEquals($Datum, $degustacija->Datum);
        $this->assertEquals($Lokacija, $degustacija->Lokacija);
        $this->assertEquals($Kapacitet, $degustacija->Kapacitet);
        $this->assertEquals($user_id, $degustacija->user_id);
        $this->assertEquals($status_degustacija_id, $degustacija->status_degustacija_id);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $degustacija = Degustacija::factory()->create();

        $response = $this->delete(route('degustacijas.destroy', $degustacija));

        $response->assertRedirect(route('degustacijas.index'));

        $this->assertModelMissing($degustacija);
    }
}
