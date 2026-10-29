<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    if (session()->has('user_id')) {
        return redirect(
            session('role') === 'siswa'
                ? route('siswa.dashboard')
                : route('admin.dashboard')
        );
    }

    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $data = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = DB::table('users')
        ->where('username', $data['username'])
        ->first();

    if (!$user || !hash_equals(
        (string) $user->password,
        (string) $data['password']
    )) {
        return back()->withErrors([
            'login' => 'Username atau password salah.',
        ])->onlyInput('username');
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

    return redirect()->route('admin.dashboard');
})->name('login.proses');

Route::post('/logout', function (Request $request) {
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

Route::middleware('web')->group(function () {
    Route::get('/admin', function () {
        abort_unless(
            session('role') === 'admin'
            || session('role') === 'kasir',
            403
        );

        return app(AdminController::class)->dashboard();
    })->name('admin.dashboard');

    Route::post('/admin/menu', [
        AdminController::class, 'tambahMenu'
    ])->name('admin.menu.tambah');

    Route::put('/admin/menu/{id}', [
        AdminController::class, 'editMenu'
    ])->name('admin.menu.edit');

    Route::delete('/admin/menu/{id}', [
        AdminController::class, 'hapusMenu'
    ])->name('admin.menu.hapus');

    Route::put('/admin/pesanan/{id}', [
        AdminController::class, 'ubahStatus'
    ])->name('admin.pesanan.status');

    Route::get('/siswa', function () {
        abort_unless(session('role') === 'siswa', 403);

        return app(SiswaController::class)->dashboard();
    })->name('siswa.dashboard');

    Route::post('/siswa/pesan', [
        SiswaController::class, 'pesan'
    ])->middleware('web')->name('siswa.pesan');
});