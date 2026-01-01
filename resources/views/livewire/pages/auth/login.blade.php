<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login()
    {
        $this->validate();

        $this->form->authenticate();
        Session::regenerate();

        return redirect()->intended(route('dashboard'));
        // $this->redirectIntended(default: route('dashboard'), navigate: true);
    }
}; ?>

<div>
    <div class="card card-plain">
        <div class="card-header pb-0 text-center">
            <h4 class="font-weight-bolder">{{ __('Signin') }}</h4>
            <p class="mb-0">{{ __('Enter your email and password to sign in') }}</p>
        </div>
        <div class="card-body">
            <form wire:submit="login">
                @csrf
                <div class="mb-3">
                    <input type="email" class="form-control form-control-lg @error('form.email') is-invalid @enderror"
                        placeholder="{{ __('email') }}" wire:model="form.email" required autofocus
                        autocomplete="username">
                    <span class="text-danger"> @error('form.email')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control form-control-lg" placeholder="{{ __('password') }}"
                        aria-label="Password" wire:model="form.password" required autocomplete="current-password">
                </div>
                <div class="form-check form-switch">

                    <label class="form-check-label" for="rememberMe">
                        <input wire:model="form.remember" type="checkbox" class="form-check-input" type="checkbox"
                            id="rememberMe">
                        <span>{{ __('Remember me') }}</span>
                    </label>
                </div>
                <div class="text-center">
                    <button type=""
                        class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">{{ __('Login') }}</button>
                </div>
            </form>
        </div>
        <div class="card-footer text-center pt-0 px-lg-2 px-1">
            <p class="mb-4 text-sm mx-auto">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}"
                    class="text-primary text-gradient font-weight-bold">{{ __('Signup') }}</a>
            </p>
        </div>
    </div>
</div>
