<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $menus = Menu::query()
            ->join('kantin', 'menu.id_kantin', '=', 'kantin.id_kantin')
            ->select('menu.*', 'kantin.nama_kantin')
            ->orderBy('menu.nama_menu')
            ->get();

        $siswa = DB::table('users')
            ->where('id', session('user_id'))
            ->where('role', 'siswa')
            ->where('aktif', true)
            ->whereNull('deleted_at')
            ->first(['nis']);

        abort_if(! $siswa || ! $siswa->nis, 403);

        $pesanan = DB::table('transaksi as t')
            ->join('detail_transaksi as d', 't.id_transaksi', '=', 'd.id_transaksi')
            ->join('menu as m', 'd.id_menu', '=', 'm.id_menu')
            ->where('t.nis', $siswa->nis)
            ->select(
                't.status',
                't.status_bayar',
                't.metode_bayar',
                't.tanggal',
                'd.jumlah',
                'd.subtotal as total_harga',
                'm.nama_menu'
            )
            ->orderByDesc('t.id_transaksi')
            ->get();

        return view('siswa.dashboard', compact('menus', 'pesanan'));
    }

    public function pesan(Request $request)
    {
        $data = $request->validate([
            'menu_id' => 'required|integer|exists:menu,id_menu',
            'jumlah' => 'required|integer|min:1|max:100',
            'metode_bayar' => 'required|in:Tunai,QRIS',
        ]);

        $siswa = DB::table('users')
            ->where('id', session('user_id'))
            ->where('role', 'siswa')
            ->where('aktif', true)
            ->whereNull('deleted_at')
            ->first(['nis']);

        abort_if(! $siswa || ! $siswa->nis, 403);

        $menu = Menu::findOrFail($data['menu_id']);

        DB::transaction(function () use ($data, $menu, $siswa) {
            $transaksi = Transaksi::create([
                'nis' => $siswa->nis,
                'id_kantin' => $menu->id_kantin,
                'metode_bayar' => $data['metode_bayar'],
            ]);

            $transaksi->details()->create([
                'id_menu' => $menu->id_menu,
                'jumlah' => $data['jumlah'],
                'harga_saat_transaksi' => $menu->harga,
                'subtotal' => $menu->harga * $data['jumlah'],
            ]);
        });

        return redirect()->route('siswa.dashboard')
            ->with('sukses', 'Pesanan berhasil dibuat.');
    }
}
