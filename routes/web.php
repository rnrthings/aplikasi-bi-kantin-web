<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;

// Halaman utama
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/tes', function () {
    return 'Laravel berjalan dengan baik!';
});

Route::get('/tes-blade', function () {
    return view('login');
});

// Halaman login
Route::get('/login', function () {
    if (session()->has('user_id')) {
        if (session('role') === 'siswa') {
            return redirect()->route('siswa.dashboard');
        }

        if (session('role') === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if (session('role') === 'kasir') {
            return redirect('/kasir');
        }

        abort(403);
    }

    return view('login');
})->name('login');

// Proses login
Route::post('/login', function (Request $request) {
    $data = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = DB::table('users')
        ->where('username', $data['username'])
        ->first();

    if (
        !$user ||
        !hash_equals(
            (string) $user->password,
            (string) $data['password']
        )
    ) {
        return back()
            ->withErrors([
                'login' => 'Username atau password salah.',
            ])
            ->onlyInput('username');
    }

    $request->session()->regenerate();

    $request->session()->put([
        'user_id' => $user->id,
        'username' => $user->username,
        'role' => $user->role,
    ]);

    if ($user->role === 'siswa') {
        return redirect()->route('siswa.dashboard');
    }

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'kasir') {
        return redirect('/kasir');
    }

    $request->session()->invalidate();

    abort(403);
})->name('login.proses');

// Logout
Route::post('/logout', function (Request $request) {
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

// Route Admin
Route::middleware('role:admin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        Route::post('/menu', [AdminController::class, 'tambahMenu'])
            ->name('menu.tambah');

        Route::put('/menu/{id}', [AdminController::class, 'editMenu'])
            ->name('menu.edit');

        Route::delete('/menu/{id}', [AdminController::class, 'hapusMenu'])
            ->name('menu.hapus');

        Route::put('/pesanan/{id}', [AdminController::class, 'ubahStatus'])
            ->name('pesanan.status');
    });

// Route Siswa
Route::middleware('role:siswa')
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('/', [SiswaController::class, 'dashboard'])
            ->name('dashboard');

        Route::post('/pesan', [SiswaController::class, 'pesan'])
            ->name('pesan');
    });