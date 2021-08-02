<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Organization;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class UsersController extends Controller
{
    
    public function index()
    {
        return Inertia::render('Users/Index', [
            'filters' => Request::all('search', 'owner','role', 'trashed'),
            'users' => Auth::user()->account->users()
                ->orderByName()
                ->filter(Request::only('search', 'owner','role','organization', 'trashed'))
                ->get()
                ->transform(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'owner' => $user->owner,
                    'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                    'deleted_at' => $user->deleted_at,
                    'role'=>$user->getRoleNames(),                    
                    'organization' => $user->organization ? $user->organization->only('name') : '',
                ]),                
                'roles'=>Role::orderBy('name')
                    ->get()
                    ->map->only('id','name'),
                'organizations'=>Organization::orderBy('name')
                ->get()
                ->map->only('id','name'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create',[
            'roles'=>Role::orderBy('name')
            ->get()
            ->map->only('id','name'),
            'organizations'=>Organization::orderBy('name')
                ->get()
                ->map->only('id','name'),
        ]);
    }

    public function store()
    {
        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
            'organization_id' => ['required'],
            'role'=>['required'],
        ]);

        $user=Auth::user()->account->users()->create([
            'first_name' => Request::get('first_name'),
            'last_name' => Request::get('last_name'),
            'email' => Request::get('email'),
            'password' => Request::get('password'),
            'owner' => Request::get('owner'),
            'organization_id' => Request::get('organization_id'),
            'photo_path' => Request::file('photo') ? Request::file('photo')->store('users') : null,
        ]);

        $user->assignRole(Request::get('role'));

        return Redirect::route('users')->with('success', 'Usuario creado.');
    }

    public function edit(User $user)
    {
        //dd($user->roles);
        //solo puede editar el usuario con el mismo id
        if (!($user->id === Auth::user()->id) && !(Auth::user()->hasRole('ADMINISTRADOR'))) {
            abort(403,'Unauthorized Action');
            # code...
        }
        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'owner' => $user->owner,
                'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'deleted_at' => $user->deleted_at,
                'organization_id' => $user->organization_id,
                'roles' => $user->roles,
            ],  
            'roles'=>Role::orderBy('name')
            ->get()
            ->map->only('id','name'),
            'organizations'=>Organization::orderBy('name')
                ->get()
                ->map->only('id','name'),
        ]);
    }

    public function update(User $user)
    {
        if (App::environment('demo') && $user->isDemoUser()) {
            return Redirect::back()->with('error', 'Updating the demo user is not allowed.');
        }

        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'organization_id' => ['required'],
            'role' => ['required'],
            'photo' => ['nullable', 'image'],
            
        ]);

        $user->update(Request::only('first_name', 'last_name', 'email', 'owner','organization_id'));

        //remove role firts
        $user->removeRole(Request::get('role'));

        //then assign role 
        $user->assignRole(Request::get('role'));

        if (Request::file('photo')) {
            $user->update(['photo_path' => Request::file('photo')->store('users')]);
        }

        if (Request::get('password')) {
            $user->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', 'Usuario modificado.');
    }

    public function destroy(User $user)
    {
        if (App::environment('demo') && $user->isDemoUser()) {
            return Redirect::back()->with('error', 'Deleting the demo user is not allowed.');
        }

        $user->delete();

        return Redirect::back()->with('success', 'Usuario eliminado.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return Redirect::back()->with('success', 'Usuario restaurado.');
    }
}
