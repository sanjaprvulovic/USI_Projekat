<?php

namespace App\Http\Controllers;

use App\Http\Requests\DegustacioniPaketStoreRequest;
use App\Http\Requests\DegustacioniPaketUpdateRequest;
use App\Models\DegustacioniPaket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DegustacioniPaketController extends Controller
{
    public function index(Request $request): View
    {
        $degustacioniPakets = DegustacioniPaket::all();

        return view('degustacioniPaket.index', [
            'degustacioniPakets' => $degustacioniPakets,
        ]);
    }

    public function create(Request $request): View
    {
        return view('degustacioniPaket.create');
    }

    public function store(DegustacioniPaketStoreRequest $request): RedirectResponse
    {
        $degustacioniPaket = DegustacioniPaket::create($request->validated());

        $request->session()->flash('degustacioniPaket.id', $degustacioniPaket->id);

        return redirect()->route('degustacioniPakets.index');
    }

    public function show(Request $request, DegustacioniPaket $degustacioniPaket): View
    {
        return view('degustacioniPaket.show', [
            'degustacioniPaket' => $degustacioniPaket,
        ]);
    }

    public function edit(Request $request, DegustacioniPaket $degustacioniPaket): View
    {
        return view('degustacioniPaket.edit', [
            'degustacioniPaket' => $degustacioniPaket,
        ]);
    }

    public function update(DegustacioniPaketUpdateRequest $request, DegustacioniPaket $degustacioniPaket): RedirectResponse
    {
        $degustacioniPaket->update($request->validated());

        $request->session()->flash('degustacioniPaket.id', $degustacioniPaket->id);

        return redirect()->route('degustacioniPakets.index');
    }

    public function destroy(Request $request, DegustacioniPaket $degustacioniPaket): RedirectResponse
    {
        $degustacioniPaket->delete();

        return redirect()->route('degustacioniPakets.index');
    }
}
