<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Events\UserSaved;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'prefixname' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'suffixname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'photo' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'type' => ['required', 'string', 'lowercase', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            
        ]);
        Log::info("reached controller ". __LINE__);
        $user = User::create([
            'prefixname' => $request->prefixname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'suffixname' => $request->suffixname,
            'username' => $request->username,
            'photo'=> $request->photo, 
            'email' => $request->email,
            'type' => $request->type,
            'password' =>  Hash::make($request->password),
        ]);
        event(new Registered($user));        
        Auth::login($user);
        event(new UserSaved($user));

        return redirect(route('dashboard', absolute: false));
    }
}
