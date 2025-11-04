<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Importamos Hash por si acaso, aunque es mejor dejar que el modelo lo haga
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new
// Atributo para usar el layout de estudiante
#[Layout('components.layouts.student')] 

// Definición de la clase del componente
class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Actualiza la contraseña del usuario.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            // CORRECCIÓN: Era $this.reset, se cambió a $this->reset
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        // Actualizamos el usuario.
        // Dejamos que el Model (User) se encargue del hasheo,
        // tal como lo hace el archivo original de settings.
        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        // CORRECCIÓN: Era $this.reset, se cambió a $this->reset
        $this->reset('current_password', 'password', 'password_confirmation');
        
        // Despachamos el evento de éxito
        $this->dispatch('password-updated');
    }
};
?>

{{-- TU HTML SE QUEDA IGUAL (Esto está bien) --}}
<section class="w-full">
    @include('partials.settings-heading')
    

            <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <flux:input
                wire:model="current_password"
                :label="__('Current password')"
                type="password"
                required
                autocomplete="current-password"
            />
            <flux:input
                wire:model="password"
                :label="__('New password')"
                type="password"
                required
                autocomplete="new-password"
            />
            <flux:input
                wire:model="password_confirmation"
                :label="__('Confirm Password')"
                type="password"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" data-test="update-password-button">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

</section>

