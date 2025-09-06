<?php

namespace App\Http\Controllers;

use App\Models\DegustacioniPaket;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\DegustacioniPaketStoreRequest;
use App\Http\Requests\DegustacioniPaketUpdateRequest;

class DegustacioniPaketController extends Controller
{
    public function __construct()
    {
        // dodatna zaštita (pored middleware-a u web.php)
        $this->middleware(['auth','can:admin']);
    }

    public function index(): View
    {
        $paketi = DegustacioniPaket::orderBy('NazivPaketa')->get();
        return view('degustacioniPaket.index', compact('paketi'));
    }

    public function create(): View
    {
        return view('degustacioniPaket.create');
    }

    public function store(DegustacioniPaketStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DegustacioniPaket::create([
            'NazivPaketa' => $data['NazivPaketa'],
            'Cena'        => $data['Cena'],
            'Opis'        => $data['Opis'] ?? null,
        ]);

        return redirect()
            ->route('degustacioni-pakets.index')
            ->with('success', 'Paket je uspešno kreiran.');
    }

    public function edit(DegustacioniPaket $degustacioni_paket): View
    {
        return view('degustacioniPaket.edit', compact('degustacioni_paket'));
    }

    public function update(DegustacioniPaketUpdateRequest $request, DegustacioniPaket $degustacioni_paket): RedirectResponse
    {
        $data = $request->validated();

        $degustacioni_paket->update([
            'NazivPaketa' => $data['NazivPaketa'],
            'Cena'        => $data['Cena'],
            'Opis'        => $data['Opis'] ?? null,
        ]);

        return redirect()
            ->route('degustacioni-pakets.index')
            ->with('success', 'Paket je uspešno ažuriran.');
    }

    public function destroy(DegustacioniPaket $degustacioni_paket): RedirectResponse
    {
        $degustacioni_paket->delete();

        return redirect()
            ->route('degustacioni-pakets.index')
            ->with('success', 'Paket je obrisan.');
    }
}
