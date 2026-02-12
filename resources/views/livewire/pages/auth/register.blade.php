<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="card card-plain">
        <div class="card-header pb-0 text-center">
            <h4 class="font-weight-bolder">{{ __('Signup') }}</h4>
            {{-- <p class="mb-0">{{ __('Welcome t') }}</p> --}}
        </div>
        <div class="card-body">
            <form wire:submit="register">
                <div class="mb-3">
                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" placeholder="{{ __('name') }}" wire:model="name"  autofocus autocomplete="username">
                    <span class="text-danger"> @error('name') {{ $message }}@enderror</span>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="{{ __('email') }}" wire:model="email"  autofocus autocomplete="username">
                    <span class="text-danger"> @error('email') {{ $message }}@enderror</span>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="{{ __('password') }}" aria-label="Password" wire:model="password"  autocomplete="current-password">
                    <span class="text-danger"> @error('password') {{ $message }}@enderror</span>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" placeholder="{{ __('confirm_password') }}" aria-label="Password" wire:model="password_confirmation" >
                    <span class="text-danger"> @error('password_confirmation') {{ $message }}@enderror</span>
                </div>
                <div class="text-center">
                    <button type="" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">{{ __('register') }}</button>
                </div>
            </form>
        </div>
        <div class="card-footer text-center pt-0 px-lg-2 px-1">
            <p class="mb-4 text-sm mx-auto">
                {{ __("Already have account?") }}
                <a href="{{ route('login') }}" class="text-primary text-gradient font-weight-bold">{{ __('Signin') }}</a>
            </p>
        </div>
    </div>
</div>
