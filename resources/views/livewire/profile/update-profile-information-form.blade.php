<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', message: __('Profile updated successfully.'));
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <div class="row">
        <div class="col-6">
            <form wire:submit="updateProfileInformation" class="">
                <div>
                    <label>{{ __('name') }}</label>
                    <input type="text" name="name" id="name" wire:model="name" class="form-control @error('name') is-invalid @enderror" required autofocus autocomplete="name" />
                    <span>@error('name') {{ $message }}@enderror</span>
                </div>

                <div>
                    <label for="">{{ __('email') }}</label>
                    <input type="email" name="" id="" wire:model="email" class="form-control @error('email') is-invalid @enderror" required autocomplete="username" />
                    <span>@error('email') {{ $message }}@enderror</span>

                    @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                        @endif
                    </div>
                    @endif
                </div>

                <div class="pt-4 items-center gap-4">
                    <button type="submit" class="btn btn-primary">{{ __('save') }}</button>


                </div>
            </form>
        </div>
    </div>
    @script
    <script>
        $wire.on('profile-updated', (x) => {
            Swal.fire({
                position: 'top-center'
                , icon: 'success'
                , title: x.message
                , showConfirmButton: false
                , timer: 1500
            })
        });

    </script>
    @endscript
</section>
