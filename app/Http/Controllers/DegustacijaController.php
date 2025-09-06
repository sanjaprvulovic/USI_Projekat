<?php

namespace App\Http\Controllers;

use App\Http\Requests\DegustacijaStoreRequest;
use App\Http\Requests\DegustacijaUpdateRequest;
use App\Models\Degustacija;
use App\Models\StatusDegustacija;
use App\Models\DegustacioniPaket;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DegustacijaController extends Controller
{
    public function __construct()
    {
        // index + show su javni; ostalo samo ulogovani
        $this->middleware('auth')->except(['index', 'show']);
        // kreiranje/izmena/brisanje samo Menadžer dogadjaja ili Administrator
        $this->middleware('can:managerOrAdmin')->only(['create','store','edit','update','destroy','paketi','paketiUpdate']);
    }

    /**
     * Lista degustacija (javna)
     */
    public function index(Request $request): View
    {
        $degustacijas = Degustacija::orderBy('Datum')->get();

        return view('degustacija.index', [
            'degustacijas' => $degustacijas,
        ]);
    }

    /**
     * Detalj degustacije (javno)
     */
    public function show(Degustacija $degustacija): View
    {
        return view('degustacija.show', compact('degustacija'));
    }

    /**
     * Forma za kreiranje (manager/admin)
     */
    public function create(): View
    {
        return view('degustacija.create');
    }

    /**
     * Snimi novu degustaciju (manager/admin)
     */
    public function store(DegustacijaStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // status "Planirana" ako ne postoji
        $statusId = StatusDegustacija::firstOrCreate(['Naziv' => 'Planirana'])->id;

        $degustacija = Degustacija::create([
            'Naziv'                 => $data['Naziv'],
            'Datum'                 => $data['Datum'],
            'Lokacija'              => $data['Lokacija'],
            'Kapacitet'             => $data['Kapacitet'],
            'user_id'               => auth()->id(),
            'status_degustacija_id' => $statusId,
        ]);

        return redirect()
            ->route('degustacijas.show', $degustacija)
            ->with('success', 'Degustacija je uspešno kreirana. Sada dodeli pakete ovoj degustaciji.');
    }

    /**
     * Forma za izmenu (manager/admin)
     */
    public function edit(Degustacija $degustacija): View
    {
        return view('degustacija.edit', compact('degustacija'));
    }

    /**
     * Ažuriraj degustaciju (manager/admin)
     */
    public function update(DegustacijaUpdateRequest $request, Degustacija $degustacija): RedirectResponse
    {
        $data = $request->validated();

        $degustacija->update([
            'Naziv'     => $data['Naziv'],
            'Datum'     => $data['Datum'],
            'Lokacija'  => $data['Lokacija'],
            'Kapacitet' => $data['Kapacitet'],
            // user_id i status obično ne menjamo ovde; po potrebi dodaj
        ]);

        return redirect()
            ->route('degustacijas.show', $degustacija)
            ->with('success', 'Degustacija je uspešno ažurirana.');
    }

    /**
     * Obriši degustaciju (manager/admin)
     */
    public function destroy(Degustacija $degustacija): RedirectResponse
    {
        $degustacija->delete();

        return redirect()
            ->route('degustacijas.index')
            ->with('success', 'Degustacija je obrisana.');
    }

    /**
     * (Opcionalno) Dodela paketa degustaciji – forma (manager/admin)
     * Ruta: GET degustacijas/{degustacija}/paketi
     */
    public function paketi(Degustacija $degustacija): View
    {
        $sviPaketi = DegustacioniPaket::orderBy('NazivPaketa')->get();
        $odabrani  = $degustacija->paketi()->pluck('degustacioni_paket_id')->toArray();

        return view('degustacija.paketi', compact('degustacija','sviPaketi','odabrani'));
    }

    /**
     * (Opcionalno) Snimi dodelu paketa (manager/admin)
     * Ruta: PUT degustacijas/{degustacija}/paketi
     */
    public function paketiUpdate(Request $request, Degustacija $degustacija): RedirectResponse
    {
        $ids = $request->input('paketi', []);   // niz ID-jeva checkbox-ova
        $degustacija->paketi()->sync($ids);     // magija: doda/ukloni što treba

        return redirect()
            ->route('degustacijas.show', $degustacija)
            ->with('success', 'Paketi su uspešno sačuvani.');
    }
}
