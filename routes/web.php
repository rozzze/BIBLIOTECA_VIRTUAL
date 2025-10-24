<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

use App\Livewire\Admin\Dashboard;
use App\Livewire\Catalog\Index as CatalogIndex;

/*
|--------------------------------------------------------------------------
| Rutas principales del sistema de Biblioteca Virtual
|--------------------------------------------------------------------------
*/

// 🌐 Página principal (landing pública)
Route::view('/', 'welcome')->name('landing');

// 📚 Catálogo (solo visible para usuarios con rol "Alumno")
Route::middleware(['auth', 'role:Alumno'])->group(function () {
    Route::get('/catalog', CatalogIndex::class)->name('catalog');
});

// ⚙️ Panel administrativo (solo Admin o Bibliotecario)
Route::middleware(['auth', 'role:Administrador|Bibliotecario'])->group(function () {
    Route::get('/admin/dashboard', Dashboard::class)->name('admin.dashboard');
});

// 🔁 Redirección automática post-login (según rol)
Route::get('/redirect-after-login', function () {
    $user = Auth::user();

    if ($user->hasRole('Administrador') || $user->hasRole('Bibliotecario')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole('Alumno')) {
        return redirect()->route('catalog');
    }

    return redirect()->route('landing');
})->middleware('auth');


/*
|--------------------------------------------------------------------------
| Rutas del perfil y ajustes (starter kit de Livewire)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

/*
|--------------------------------------------------------------------------
| Rutas de autenticación (login, registro, etc.)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
