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
use Illuminate\Support\Facades\Gate;

class DegustacijaController extends Controller
{
    public function __construct()
    {
        
        $this->middleware('auth')->except(['index', 'show']);

        
        $this->middleware('can:manager')->only([
            'create','store','paketi','paketiUpdate'
        ]);

        
        $this->middleware('can:managerOrAdmin')->only([
            'edit','update','destroy','finish','cancel'
        ]);
    }

    
    public function index(Request $request): View
    {
        $query = Degustacija::withCount('aktivnePrijave')
            ->with('paketi')
            ->latest('Datum');

        
        if (!Gate::allows('managerOrAdmin')) {
            $query->has('paketi'); // bar 1 paket
        }

        $degustacijas = $query->get();

        return view('degustacija.index', compact('degustacijas'));
    }

    
    public function show(Degustacija $degustacija): View | RedirectResponse
    {
        
        $degustacija->load('paketi');

        
        if (!Gate::allows('managerOrAdmin') && $degustacija->paketi->isEmpty()) {
            return redirect()
                ->route('degustacijas.index')
                ->with('success', 'Ova degustacija trenutno nema dodeljene pakete.');
        }

        return view('degustacija.show', compact('degustacija'));
    }

    
    public function create(): View
    {
        return view('degustacija.create');
    }

    
    public function store(DegustacijaStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        
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

    
    public function edit(Degustacija $degustacija): View
    {
        return view('degustacija.edit', compact('degustacija'));
    }

    
    public function update(DegustacijaUpdateRequest $request, Degustacija $degustacija): RedirectResponse
    {
        $data = $request->validated();

        $degustacija->update([
            'Naziv'     => $data['Naziv'],
            'Datum'     => $data['Datum'],
            'Lokacija'  => $data['Lokacija'],
            'Kapacitet' => $data['Kapacitet'],
        ]);

        return redirect()
            ->route('degustacijas.show', $degustacija)
            ->with('success', 'Degustacija je uspešno ažurirana.');
    }

    
    public function destroy(Degustacija $degustacija): RedirectResponse
    {
        $degustacija->delete();

        return redirect()
            ->route('degustacijas.index')
            ->with('success', 'Degustacija je obrisana.');
    }

    
    public function paketi(Degustacija $degustacija): View
    {
        $sviPaketi = DegustacioniPaket::orderBy('NazivPaketa')->get();
        $odabrani  = $degustacija->paketi()->pluck('degustacioni_paket_id')->toArray();

        return view('degustacija.paketi', compact('degustacija','sviPaketi','odabrani'));
    }

    
    public function paketiUpdate(Request $request, Degustacija $degustacija): RedirectResponse
    {
        $ids = $request->input('paketi', []);   // niz ID-jeva checkbox-ova
        $degustacija->paketi()->sync($ids);

        return redirect()
            ->route('degustacijas.show', $degustacija)
            ->with('success', 'Paketi su uspešno sačuvani.');
    }

   
    public function finish(Degustacija $degustacija): RedirectResponse
    {
        $status = optional($degustacija->statusDegustacija)->Naziv;

        if ($status === 'Završena') {
            return back()->with('success', 'Degustacija je već označena kao završena.');
        }
        if ($status === 'Otkazana') {
            return back()->with('success', 'Otkazana degustacija se ne može označiti kao završena.');
        }
        if ($degustacija->Datum && now()->lt($degustacija->Datum)) {
            return back()->with('success', 'Ne možeš završiti degustaciju pre njenog početka.');
        }

        $finishedId = StatusDegustacija::firstOrCreate(['Naziv' => 'Završena'])->id;

        $degustacija->update([
            'status_degustacija_id' => $finishedId,
        ]);

        return back()->with('success', 'Degustacija je označena kao završena.');
    }

    
    public function cancel(Degustacija $degustacija): RedirectResponse
    {
        $status = optional($degustacija->statusDegustacija)->Naziv;

        if ($status === 'Otkazana') {
            return back()->with('success', 'Degustacija je već otkazana.');
        }
        if ($status === 'Završena') {
            return back()->with('success', 'Završenu degustaciju nije moguće otkazati.');
        }
        if ($degustacija->Datum && now()->gte($degustacija->Datum)) {
            return back()->with('success', 'Degustacija je već počela; otkazivanje nije moguće.');
        }

        $canceledId = StatusDegustacija::firstOrCreate(['Naziv' => 'Otkazana'])->id;

        $degustacija->update([
            'status_degustacija_id' => $canceledId,
        ]);

        return back()->with('success', 'Degustacija je označena kao otkazana.');
    }
}
