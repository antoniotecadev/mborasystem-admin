<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserDeleteRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function index()
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            return Inertia::render('Users/Index', [
                'filters' => Request::all('search', 'role', 'trashed'),
                'users' => new UserCollection(
                    Auth::user()->account->users()
                        ->orderByName()
                        ->filter(Request::only('search', 'role', 'trashed'))
                        ->paginate()
                        ->appends(Request::all())
                ),
            ]);
        }
    }

    public function create()
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            return Inertia::render('Users/Create');
        }
    }

    public function store(UserStoreRequest $request)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            Auth::user()->account->users()->create(
                $request->validated()
            );
            Log::channel('daily')->alert('Usuário <<' . $request->first_name . ' ' . $request->last_name . ' - ' . $request->email . '>> criado.',[ 'id' => Auth::id(), 'nome' => Auth::user()->first_name . " " . Auth::user()->last_name, 'email' =>  Auth::user()->email]);
            return Redirect::route('users')->with('success', 'Utilizador criado.');
        }
    }

    public function edit(User $user)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            return Inertia::render('Users/Edit', [
                'user' => new UserResource($user),
            ]);
        }
    }

    public function update(User $user, UserUpdateRequest $request)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            $user->update(
                $request->validated()
            );
            Log::channel('daily')->alert('Usuário <<' . $request->first_name . ' ' . $request->last_name . ' - ' . $request->email . '>> actualizado.',[ 'id' => Auth::id(), 'nome' => Auth::user()->first_name . " " . Auth::user()->last_name, 'email' =>  Auth::user()->email]);
            return Redirect::back()->with('success', 'Utilizador actualizado.');
        }
    }

    public function destroy(User $user, UserDeleteRequest $request)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            $user->delete();
            Log::channel('daily')->emergency('Usuário <<' . $user->first_name . ' ' . $user->last_name . ' - ' . $user->email . '>> eliminado.',[ 'id' => Auth::id(), 'nome' => Auth::user()->first_name . " " . Auth::user()->last_name, 'email' =>  Auth::user()->email]);
            return Redirect::back()->with('success', 'Utilizador eliminado.');
        }
    }

    public function restore(User $user)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->allowed()) {
            $user->restore();
            Log::channel('daily')->emergency('Usuário <<' . $user->first_name . ' ' . $user->last_name . ' - ' . $user->email . '>> restaurado.',[ 'id' => Auth::id(), 'nome' => Auth::user()->first_name . " " . Auth::user()->last_name, 'email' =>  Auth::user()->email]);
            return Redirect::back()->with('success', 'Utilizador restaurado.');
        }
    }
}
