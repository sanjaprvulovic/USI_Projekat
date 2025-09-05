<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrIjavaStoreRequest;
use App\Http\Requests\PrIjavaUpdateRequest;
use App\Models\PrIjava;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PrIjavaController extends Controller
{
    public function index(Request $request): View
    {
        $prIjavas = PrIjava::all();

        return view('prIjava.index', [
            'prIjavas' => $prIjavas,
        ]);
    }

    public function create(Request $request): View
    {
        return view('prIjava.create');
    }

    public function store(PrIjavaStoreRequest $request): RedirectResponse
    {
        $prIjava = PrIjava::create($request->validated());

        $request->session()->flash('prIjava.id', $prIjava->id);

        return redirect()->route('prIjavas.index');
    }

    public function show(Request $request, PrIjava $prIjava): View
    {
        return view('prIjava.show', [
            'prIjava' => $prIjava,
        ]);
    }

    public function edit(Request $request, PrIjava $prIjava): View
    {
        return view('prIjava.edit', [
            'prIjava' => $prIjava,
        ]);
    }

    public function update(PrIjavaUpdateRequest $request, PrIjava $prIjava): RedirectResponse
    {
        $prIjava->update($request->validated());

        $request->session()->flash('prIjava.id', $prIjava->id);

        return redirect()->route('prIjavas.index');
    }

    public function destroy(Request $request, PrIjava $prIjava): RedirectResponse
    {
        $prIjava->delete();

        return redirect()->route('prIjavas.index');
    }
}
