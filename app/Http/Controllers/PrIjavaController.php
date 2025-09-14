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
        $this->middleware('can:managerOrAdmin')->only(['forDegustacija']);

        // akcije nad prijavom SAMO menadžer
        $this->middleware('can:manager')->only([
            'approve', 'reject', 'updateStatus', 'checkIn'
        ]);
    }

    /**
     * Prikaz prijava.
     * - manager/admin: sve prijave
     * - klijent: samo svoje prijave
     */
    public function index(): View
    {
        if (Gate::allows('managerOrAdmin')) {
            $prijave = PrIjava::with([
                    'user',
                    'degustacija.paketi',    // <— dodato
                    'degustacioniPaket',
                    'statusPrijava'
                ])
                ->latest()->paginate(20);
        } else {
            Gate::authorize('client');
            $prijave = PrIjava::with([
                    'degustacija.paketi',    // <— dodato
                    'degustacioniPaket',
                    'statusPrijava'
                ])
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

        // stanje degustacije
        $deg = Degustacija::findOrFail($data['degustacija_id']);
        $degStatus = optional($deg->statusDegustacija)->Naziv;

        if (in_array($degStatus, ['Otkazana','Završena'], true)) {
            return back()->with('success', 'Nije moguće poslati prijavu: degustacija je ' . strtolower($degStatus) . '.');
        }

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
     * (Klijent) Izmena prijave — promena paketa DOk je Na čekanju.
     */
    public function update(Request $request, PrIjava $prijava): RedirectResponse
    {
        Gate::authorize('client');

        // samo vlasnik može menjati svoju prijavu
        if ((int) $prijava->user_id !== (int) auth()->id()) {
            abort(403);
        }

        // dozvoljeno samo ako je Na čekanju
        $cekId = StatusPrijava::firstOrCreate(['Naziv' => 'Na čekanju'])->id;
        if ((int) $prijava->status_prijava_id !== (int) $cekId) {
            return back()->with('success', 'Paket možeš menjati samo dok je prijava u statusu „Na čekanju“.');
        }

        // izabrani paket
        $data = $request->validate([
            'degustacioni_paket_id' => ['required','exists:degustacioni_pakets,id'],
        ]);

        // paket mora biti dodeljen baš ovoj degustaciji
        $deg = $prijava->degustacija;
        $paketOk = $deg->paketi()
            ->where('degustacioni_paket_id', $data['degustacioni_paket_id'])
            ->exists();

        if (! $paketOk) {
            return back()->with('success', 'Izabrani paket nije dodeljen ovoj degustaciji.');
        }

        $prijava->update([
            'degustacioni_paket_id' => $data['degustacioni_paket_id'],
        ]);

        return back()->with('success', 'Paket na prijavi je uspešno ažuriran.');
    }

    /**
     * (Klijent) Otkaz prijave -> status "Otkazana"
     */
    public function destroy(PrIjava $prijava): RedirectResponse
    {
        // samo vlasnik može da otkaže svoju prijavu
        if ((int) $prijava->user_id !== (int) auth()->id()) {
            abort(403);
        }

        $otkazanaId = StatusPrijava::firstOrCreate(['Naziv' => 'Otkazana'])->id;

        // Ako je već otkazana, samo poruka
        if ((int) $prijava->status_prijava_id === (int) $otkazanaId) {
            return back()->with('success', 'Prijava je već otkazana.');
        }

        $stName = optional($prijava->statusPrijava)->Naziv;
        if (! in_array($stName, ['Na čekanju', 'Prihvaćena'])) {
            return back()->with('success', 'Ovu prijavu nije moguće otkazati.');
        }

        // zabrana otkaza ako je degustacija otkazana/završena ili prerok
        $deg = $prijava->degustacija;
        if ($deg) {
            $degStatus = optional($deg->statusDegustacija)->Naziv;
            if (in_array($degStatus, ['Otkazana','Završena'], true)) {
                return back()->with('success', 'Prijavu nije moguće otkazati jer je degustacija ' . strtolower($degStatus) . '.');
            }

            if ($deg->Datum) {
                if (now()->gte($deg->Datum)) {
                    return back()->with('success', 'Degustacija je već započela; prijavu nije moguće otkazati.');
                }
                if (now()->diffInHours($deg->Datum) < 24) {
                    return back()->with('success', 'Prijavu nije moguće otkazati u poslednjih 24h pre početka.');
                }
            }
        }

        $prijava->update(['status_prijava_id' => $otkazanaId]);

        return back()->with('success', 'Prijava je otkazana.');
    }

    public function checkIn(PrIjava $prijava): RedirectResponse
    {
        Gate::authorize('managerOrAdmin');

        $deg = $prijava->degustacija;
        $degStatus = optional($deg->statusDegustacija)->Naziv;

        if (in_array($degStatus, ['Otkazana','Završena'], true)) {
            return back()->with('success', 'Ne može se beležiti prisustvo: degustacija je ' . strtolower($degStatus) . '.');
        }

        if ($deg && $deg->Datum && now()->lt($deg->Datum)) {
            return back()->with('success', 'Prerano je za evidenciju prisustva – degustacija još nije počela.');
        }

        // samo za PRIHVAĆENE prijave ima smisla
        $acceptedId = StatusPrijava::firstOrCreate(['Naziv' => 'Prihvaćena'])->id;
        if ((int) $prijava->status_prijava_id !== (int) $acceptedId) {
            return back()->with('success', 'Samo prijave u statusu „Prihvaćena“ mogu biti označene kao prisutne.');
        }

        if (! $prijava->prisutan) {
            $prijava->update([
                'prisutan'      => true,
                'checked_in_at' => now(),
            ]);
            $msg = 'Klijent je označen kao prisutan.';
        } else {
            $prijava->update([
                'prisutan'      => false,
                'checked_in_at' => null,
            ]);
            $msg = 'Prisustvo je poništeno.';
        }

        return back()->with('success', $msg);
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

    public function approve(PrIjava $prijava): RedirectResponse
    {
        Gate::authorize('manager');

        // degustacija ne sme biti otkazana/završena
        $deg = $prijava->degustacija;
        $degStatus = optional($deg->statusDegustacija)->Naziv;
        if (in_array($degStatus, ['Otkazana','Završena'], true)) {
            return back()->with('success', 'Nije moguće odobriti: degustacija je ' . strtolower($degStatus) . '.');
        }

        // Dozvoli akciju SAMO ako je Na čekanju
        $cekId = StatusPrijava::firstOrCreate(['Naziv' => 'Na čekanju'])->id;
        if ($prijava->status_prijava_id !== $cekId) {
            return back()->with('success', 'Ova prijava nije u statusu „Na čekanju“ – odobrenje nije moguće.');
        }

        // (opciono) dodatna provera kapaciteta
        if (method_exists($deg, 'remainingCapacity') && $deg->remainingCapacity() <= 0) {
            return back()->with('success', 'Nema mesta za odobrenje ove prijave.');
        }

        $prihvacenaId = StatusPrijava::firstOrCreate(['Naziv' => 'Prihvaćena'])->id;
        $prijava->update(['status_prijava_id' => $prihvacenaId]);

        return back()->with('success', 'Prijava je odobrena.');
    }

    public function reject(PrIjava $prijava): RedirectResponse
    {
        Gate::authorize('manager');

        // degustacija ne sme biti otkazana/završena
        $degStatus = optional($prijava->degustacija->statusDegustacija)->Naziv;
        if (in_array($degStatus, ['Otkazana','Završena'], true)) {
            return back()->with('success', 'Nije moguće odbiti: degustacija je ' . strtolower($degStatus) . '.');
        }

        // Dozvoli akciju SAMO ako je Na čekanju
        $cekId = StatusPrijava::firstOrCreate(['Naziv' => 'Na čekanju'])->id;
        if ($prijava->status_prijava_id !== $cekId) {
            return back()->with('success', 'Ova prijava nije u statusu „Na čekanju“ – odbijanje nije moguće.');
        }

        $odbijenaId = StatusPrijava::firstOrCreate(['Naziv' => 'Odbijena'])->id;
        $prijava->update(['status_prijava_id' => $odbijenaId]);

        return back()->with('success', 'Prijava je odbijena.');
    }
}
