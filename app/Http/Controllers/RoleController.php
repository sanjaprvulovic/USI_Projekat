<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct()
    {
        // Sve akcije su samo za ulogovanog administratora
        $this->middleware(['auth', 'can:admin']);
    }

    /* =========================
     |  Osnovni CRUD nad rolama
     |=========================*/

    public function index(Request $request): View
    {
        $roles = Role::orderBy('Naziv')->get();

        return view('role.index', compact('roles'));
    }

    public function create(Request $request): View
    {
        return view('role.create');
    }

    public function store(RoleStoreRequest $request): RedirectResponse
    {
        $role = Role::create($request->validated());

        $request->session()->flash('success', 'Uloga je uspešno kreirana.');

        return redirect()->route('roles.index');
    }

    public function show(Request $request, Role $role): View
    {
        return view('role.show', compact('role'));
    }

    public function edit(Request $request, Role $role): View
    {
        return view('role.edit', compact('role'));
    }

    public function update(RoleUpdateRequest $request, Role $role): RedirectResponse
    {
        $role->update($request->validated());

        return redirect()
            ->route('roles.index')
            ->with('success', 'Uloga je uspešno ažurirana.');
    }

    public function destroy(Request $request, Role $role): RedirectResponse
    {
        // Bezbednost: ne dozvoli brisanje role koja je dodeljena nekom korisniku
        $inUse = User::where('role_id', $role->id)->exists();
        if ($inUse) {
            return back()->with('success', 'Nije moguće obrisati ulogu jer je dodeljena korisnicima.');
        }

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Uloga je obrisana.');
    }

    /* ==========================================
     |  Admin panel za dodelu uloga korisnicima
     |==========================================*/

    /**
     * Lista korisnika + dropdown za dodelu/izmenu uloge.
     */
    public function manageUsers(): View
    {
        $users = User::with('role')->orderBy('name')->orderBy('surname')->get();
        $roles = Role::orderBy('Naziv')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    
    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role_id' => ['nullable', 'exists:roles,id'],
        ]);

        
        $currentIsAdmin = optional($user->role)->Naziv === 'Administrator';
        $newIsAdmin = $data['role_id']
            ? optional(Role::find($data['role_id']))->Naziv === 'Administrator'
            : false;

        if ($currentIsAdmin && ! $newIsAdmin) {
            $adminCount = User::whereHas('role', fn($q) => $q->where('Naziv', 'Administrator'))->count();
            if ($adminCount <= 1 && $user->id === auth()->id()) {
                return back()->with('success', 'Ne možeš sam sebe skinuti sa Administratora jer bi ostao bez ijednog admina.');
            }
        }

        $user->update([
            'role_id' => $data['role_id'] ?? null,
        ]);

        return back()->with('success', 'Uloga korisniku je uspešno ažurirana.');
    }
}
