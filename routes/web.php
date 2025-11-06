<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Livewire\Admin\ImportUsers;

use App\Livewire\Admin\Dashboard;
use App\Livewire\Catalog\Index as CatalogIndex;

use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Livewire\Admin\Users\Create as UsersCreate;
use App\Livewire\Admin\Users\Edit as UsersEdit;

use App\Livewire\Admin\Careers\Index as CareerIndex;
use App\Livewire\Admin\Careers\Create as CareerCreate;
use App\Livewire\Admin\Careers\Edit as CareerEdit;

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
    
    // AGREGA ESTA LÍNEA:
    Volt::route('/student/password', 'student.change-password')
        ->name('student.password.edit');
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

Route::middleware(['auth', 'role:Administrador'])->group(function () {
    Route::get('/admin/import-users', ImportUsers::class)->name('admin.import-users');
});

Route::middleware(['auth', 'role:Administrador'])->prefix('admin')->group(function () {
    Route::get('/users', UsersIndex::class)->name('admin.users.index');
    Route::get('/users/create', UsersCreate::class)->name('admin.users.create');
    Route::get('/users/{user}/edit', UsersEdit::class)->name('admin.users.edit');
});

Route::middleware(['auth', 'role:Administrador'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/careers', CareerIndex::class)->name('careers.index');
    Route::get('/careers/create', CareerCreate::class)->name('careers.create');
    Route::get('/careers/{career}/edit', CareerEdit::class)->name('careers.edit');
});

/*
|--------------------------------------------------------------------------
| Rutas de autenticación (login, registro, etc.)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
