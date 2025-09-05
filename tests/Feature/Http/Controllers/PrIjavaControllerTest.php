<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\PrIjava;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PrIjavaController
 */
final class PrIjavaControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $prIjavas = PrIjava::factory()->count(3)->create();

        $response = $this->get(route('pr-ijavas.index'));

        $response->assertOk();
        $response->assertViewIs('prIjava.index');
        $response->assertViewHas('prIjavas');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('pr-ijavas.create'));

        $response->assertOk();
        $response->assertViewIs('prIjava.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PrIjavaController::class,
            'store',
            \App\Http\Requests\PrIjavaStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $Datum = Carbon::parse(fake()->dateTime());
        $status_prijava_id = fake()->numberBetween(-100000, 100000);
        $degustacija_id = fake()->numberBetween(-100000, 100000);
        $user_id = fake()->numberBetween(-100000, 100000);
        $degustacioni_paket_id = fake()->numberBetween(-100000, 100000);

        $response = $this->post(route('pr-ijavas.store'), [
            'Datum' => $Datum->toDateTimeString(),
            'status_prijava_id' => $status_prijava_id,
            'degustacija_id' => $degustacija_id,
            'user_id' => $user_id,
            'degustacioni_paket_id' => $degustacioni_paket_id,
        ]);

        $prIjavas = PrIjava::query()
            ->where('Datum', $Datum)
            ->where('status_prijava_id', $status_prijava_id)
            ->where('degustacija_id', $degustacija_id)
            ->where('user_id', $user_id)
            ->where('degustacioni_paket_id', $degustacioni_paket_id)
            ->get();
        $this->assertCount(1, $prIjavas);
        $prIjava = $prIjavas->first();

        $response->assertRedirect(route('prIjavas.index'));
        $response->assertSessionHas('prIjava.id', $prIjava->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $prIjava = PrIjava::factory()->create();

        $response = $this->get(route('pr-ijavas.show', $prIjava));

        $response->assertOk();
        $response->assertViewIs('prIjava.show');
        $response->assertViewHas('prIjava');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $prIjava = PrIjava::factory()->create();

        $response = $this->get(route('pr-ijavas.edit', $prIjava));

        $response->assertOk();
        $response->assertViewIs('prIjava.edit');
        $response->assertViewHas('prIjava');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PrIjavaController::class,
            'update',
            \App\Http\Requests\PrIjavaUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $prIjava = PrIjava::factory()->create();
        $Datum = Carbon::parse(fake()->dateTime());
        $status_prijava_id = fake()->numberBetween(-100000, 100000);
        $degustacija_id = fake()->numberBetween(-100000, 100000);
        $user_id = fake()->numberBetween(-100000, 100000);
        $degustacioni_paket_id = fake()->numberBetween(-100000, 100000);

        $response = $this->put(route('pr-ijavas.update', $prIjava), [
            'Datum' => $Datum->toDateTimeString(),
            'status_prijava_id' => $status_prijava_id,
            'degustacija_id' => $degustacija_id,
            'user_id' => $user_id,
            'degustacioni_paket_id' => $degustacioni_paket_id,
        ]);

        $prIjava->refresh();

        $response->assertRedirect(route('prIjavas.index'));
        $response->assertSessionHas('prIjava.id', $prIjava->id);

        $this->assertEquals($Datum, $prIjava->Datum);
        $this->assertEquals($status_prijava_id, $prIjava->status_prijava_id);
        $this->assertEquals($degustacija_id, $prIjava->degustacija_id);
        $this->assertEquals($user_id, $prIjava->user_id);
        $this->assertEquals($degustacioni_paket_id, $prIjava->degustacioni_paket_id);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $prIjava = PrIjava::factory()->create();

        $response = $this->delete(route('pr-ijavas.destroy', $prIjava));

        $response->assertRedirect(route('prIjavas.index'));

        $this->assertModelMissing($prIjava);
    }
}
