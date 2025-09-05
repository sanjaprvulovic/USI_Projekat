<?php

namespace App\Http\Controllers;

use App\Http\Requests\DegustacijaStoreRequest;
use App\Http\Requests\DegustacijaUpdateRequest;
use App\Models\Degustacija;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Laravel\Ui\Presets\Vue;

class DegustacijaController extends Controller
{
    public function index(Request $request): View
    {
        $degustacijas = Degustacija::all();

        return view('degustacija.index', [
            'degustacijas' => $degustacijas,
        ]);
    }

    public function create(Request $request): View
    {
        return view('degustacija.create');
    }

    public function store(DegustacijaStoreRequest $request): RedirectResponse
    {
        $degustacija = Degustacija::create($request->validated());

        $request->session()->flash('degustacija.id', $degustacija->id);

        return redirect()->route('degustacijas.index');
    }

    public function show(Request $request, Degustacija $degustacija): View
    {
        return view('degustacija.show', [
            'degustacija' => $degustacija,
        ]);
    }

    public function edit(Request $request, Degustacija $degustacija): View
    {
        return view('degustacija.edit', [
            'degustacija' => $degustacija,
        ]);
    }

    public function update(DegustacijaUpdateRequest $request, Degustacija $degustacija): RedirectResponse
    {
        $degustacija->update($request->validated());

        $request->session()->flash('degustacija.id', $degustacija->id);

        return redirect()->route('degustacijas.index');
    }

    public function destroy(Request $request, Degustacija $degustacija): RedirectResponse
    {
        $degustacija->delete();

        return redirect()->route('degustacijas.index');
    }
}
