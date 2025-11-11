<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// 🧭 Livewire Components
use App\Livewire\Admin\Dashboard;
use App\Livewire\Catalog\Index as CatalogIndex;

use App\Livewire\Admin\ImportUsers;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Livewire\Admin\Users\Create as UsersCreate;
use App\Livewire\Admin\Users\Edit as UsersEdit;

use App\Livewire\Admin\Careers\Index as CareerIndex;
use App\Livewire\Admin\Careers\Create as CareerCreate;
use App\Livewire\Admin\Careers\Edit as CareerEdit;

use App\Livewire\Admin\Categories\Index as CategoryIndex;
use App\Livewire\Admin\Categories\Create as CategoryCreate;
use App\Livewire\Admin\Categories\Edit as CategoryEdit;

use App\Livewire\Admin\Authors\Index as AuthorIndex;
use App\Livewire\Admin\Authors\Create as AuthorCreate;
use App\Livewire\Admin\Authors\Edit as AuthorEdit;

use App\Livewire\Admin\Publishers\Index as PublisherIndex;
use App\Livewire\Admin\Publishers\Create as PublisherCreate;
use App\Livewire\Admin\Publishers\Edit as PublisherEdit;

use App\Livewire\Admin\Books\Index as BookIndex;
use App\Livewire\Admin\Books\Create as BookCreate;
use App\Livewire\Admin\Books\Edit as BookEdit;

/*
|--------------------------------------------------------------------------
| 🌐 RUTAS PRINCIPALES DEL SISTEMA DE BIBLIOTECA VIRTUAL
|--------------------------------------------------------------------------
*/

// Página pública principal
Route::view('/', 'welcome')->name('landing');

// 📚 CATÁLOGO (solo para rol Alumno)
Route::middleware(['auth', 'role:Alumno'])->group(function () {
    Route::get('/catalog', CatalogIndex::class)->name('catalog');

    // Cambio de contraseña (alumno)
    Volt::route('/student/password', 'student.change-password')
        ->name('student.password.edit');
});

// ⚙️ PANEL ADMINISTRATIVO (Admin o Bibliotecario)
Route::middleware(['auth', 'role:Administrador|Bibliotecario'])->group(function () {
    Route::get('/admin/dashboard', Dashboard::class)->name('admin.dashboard');
});

// 🔁 REDIRECCIÓN AUTOMÁTICA POST-LOGIN
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
| ⚙️ AJUSTES DE PERFIL (Livewire Starter Kit)
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
| 👤 ADMINISTRADOR — Importación y Gestión de Usuarios
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Administrador'])->group(function () {
    Route::get('/admin/import-users', ImportUsers::class)->name('admin.import-users');
});

Route::middleware(['auth', 'role:Administrador'])->prefix('admin')->group(function () {
    // Usuarios
    Route::get('/users', UsersIndex::class)->name('admin.users.index');
    Route::get('/users/create', UsersCreate::class)->name('admin.users.create');
    Route::get('/users/{user}/edit', UsersEdit::class)->name('admin.users.edit');

    // Carreras
    Route::get('/careers', CareerIndex::class)->name('admin.careers.index');
    Route::get('/careers/create', CareerCreate::class)->name('admin.careers.create');
    Route::get('/careers/{career}/edit', CareerEdit::class)->name('admin.careers.edit');

    // Categorías
    Route::get('/categories', CategoryIndex::class)->name('admin.categories.index');
    Route::get('/categories/create', CategoryCreate::class)->name('admin.categories.create');
    Route::get('/categories/{category}/edit', CategoryEdit::class)->name('admin.categories.edit');

    // Autores
    Route::get('/authors', AuthorIndex::class)->name('admin.authors.index');
    Route::get('/authors/create', AuthorCreate::class)->name('admin.authors.create');
    Route::get('/authors/{author}/edit', AuthorEdit::class)->name('admin.authors.edit');

    // Editoriales
    Route::get('/publishers', PublisherIndex::class)->name('admin.publishers.index');
    Route::get('/publishers/create', PublisherCreate::class)->name('admin.publishers.create');
    Route::get('/publishers/{publisher}/edit', PublisherEdit::class)->name('admin.publishers.edit');

    // Libros
    Route::get('/books', BookIndex::class)->name('admin.books.index');
    Route::get('/books/create', BookCreate::class)->name('admin.books.create');
    Route::get('/books/{book}/edit', BookEdit::class)->name('admin.books.edit');
});

/*
|--------------------------------------------------------------------------
| 🔐 AUTENTICACIÓN (Login, Registro, etc.)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
