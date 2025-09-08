<?php

namespace App\Http\Controllers;

use App\Models\Degustacija;
use App\Models\PrIjava;
use App\Models\StatusPrijava;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PrIjavaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // svi ulazi traže login
    }

    /**
     * Prikaz prijava.
     * - manager/admin: sve prijave
     * - klijent: samo svoje prijave
     */
    public function index(): View
    {
        if (Gate::allows('managerOrAdmin')) {
            $prijave = PrIjava::with(['user','degustacija','degustacioniPaket','statusPrijava'])
                ->latest()->paginate(20);
        } else {
            Gate::authorize('client');
            $prijave = PrIjava::with(['degustacija','degustacioniPaket','statusPrijava'])
                ->where('user_id', auth()->id())
                ->latest()->paginate(20);
        }

        return view('prijava.index', compact('prijave'));
    }

    /**
     * (Klijent) Kreiranje prijave.
     * Napomena: koristi hidden field "degustacija_id" (tvoj trenutni radni način).
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('client');

        $data = $request->validate([
            'degustacija_id'        => ['required','exists:degustacijas,id'],
            'degustacioni_paket_id' => ['required','exists:degustacioni_pakets,id'],
        ]);

        // spreči duplu aktivnu prijavu za istu degustaciju
        $otkazanaId = StatusPrijava::firstOrCreate(['Naziv' => 'Otkazana'])->id;
        $odbijenaId = StatusPrijava::firstOrCreate(['Naziv' => 'Odbijena'])->id;

        $postojiAktivna = PrIjava::where('user_id', auth()->id())
            ->where('degustacija_id', $data['degustacija_id'])
            ->whereNotIn('status_prijava_id', [$otkazanaId, $odbijenaId])
            ->exists();

        if ($postojiAktivna) {
            return back()->with('success', 'Već imate aktivnu prijavu za ovu degustaciju.');
        }

        // kapacitet
        $deg = Degustacija::findOrFail($data['degustacija_id']);
        if (method_exists($deg, 'isFull') && $deg->isFull()) {
            return back()->with('success', 'Nažalost, kapacitet degustacije je popunjen.');
        }

        // kreiraj prijavu
        $cekId = StatusPrijava::firstOrCreate(['Naziv' => 'Na čekanju'])->id;

        
        PrIjava::create([
            'Datum'                 => now(),
            'status_prijava_id'     => $cekId,
            'degustacija_id'        => $data['degustacija_id'],
            'user_id'               => auth()->id(),
            'degustacioni_paket_id' => $data['degustacioni_paket_id'],
        ]);



        return back()->with('success', 'Prijava je poslata. Čeka odobrenje.');
    }

    /**
     * (Klijent/Menadžer/Admin) Otkaz prijave -> status "Otkazana"
     */
    public function destroy(PrIjava $prijava): RedirectResponse
    {
       
        if ((int) $prijava->user_id !== (int) auth()->id()) {
            abort(403);
        }

        $otkazanaId = \App\Models\StatusPrijava::firstOrCreate(['Naziv' => 'Otkazana'])->id;

        // Ako je već otkazana, samo poruka
        if ((int) $prijava->status_prijava_id === (int) $otkazanaId) {
            return back()->with('success', 'Prijava je već otkazana.');
        }

        $prijava->update(['status_prijava_id' => $otkazanaId]);

        return back()->with('success', 'Prijava je otkazana.');
    }

    /**
     * (Menadžer/Admin) Pregled svih prijava za određenu degustaciju.
     */
    public function forDegustacija(Degustacija $degustacija): View
    {
        Gate::authorize('managerOrAdmin');

        $prijave = PrIjava::with(['user','degustacioniPaket','statusPrijava'])
            ->where('degustacija_id', $degustacija->id)
            ->latest()
            ->get();

        return view('prijava.for-degustacija', compact('degustacija','prijave'));
    }

    /**
     * (Menadžer/Admin) Odobri prijavu -> "Prihvaćena"
     */
    public function approve(PrIjava $prijava): RedirectResponse
    {
        Gate::authorize('managerOrAdmin');

        $deg = $prijava->degustacija;
        if (method_exists($deg, 'remainingCapacity') && $deg->remainingCapacity() <= 0) {
            return back()->with('success', 'Nema mesta za odobrenje ove prijave.');
        }

        $prihvacenaId = StatusPrijava::firstOrCreate(['Naziv' => 'Prihvaćena'])->id;
        $prijava->update(['status_prijava_id' => $prihvacenaId]);

        return back()->with('success', 'Prijava je odobrena.');
    }

    /**
     * (Menadžer/Admin) Odbij prijavu -> "Odbijena"
     */
    public function reject(PrIjava $prijava): RedirectResponse
    {
        Gate::authorize('managerOrAdmin');

        $odbijenaId = StatusPrijava::firstOrCreate(['Naziv' => 'Odbijena'])->id;
        $prijava->update(['status_prijava_id' => $odbijenaId]);

        return back()->with('success', 'Prijava je odbijena.');
    }
}
