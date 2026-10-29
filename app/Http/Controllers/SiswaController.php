<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $menus = Menu::orderBy('nama_menu')->get();

        $pesanan = Pesanan::join(
            'menu',
            'pesanan.menu_id',
            '=',
            'menu.id'
        )->where(
            'pesanan.user_id',
            session('user_id')
        )->select(
            'pesanan.*',
            'menu.nama_menu'
        )->orderBy('pesanan.id', 'desc')->get();

        return view('siswa.dashboard', compact('menus', 'pesanan'));
    }

    public function pesan(Request $request)
    {
        $data = $request->validate([
            'menu_id' => 'required|integer|exists:menu,id',
            'jumlah' => 'required|integer|min:1|max:100',
        ]);

        $menu = Menu::findOrFail($data['menu_id']);

        Pesanan::create([
            'user_id' => session('user_id'),
            'menu_id' => $menu->id,
            'jumlah' => $data['jumlah'],
            'total_harga' => $menu->harga * $data['jumlah'],
            'status' => 'Menunggu Konfirmasi',
        ]);

        return redirect()->route('siswa.dashboard')
            ->with('sukses', 'Pesanan berhasil dibuat.');
    }
}