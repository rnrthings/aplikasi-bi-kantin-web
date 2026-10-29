<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $menus = Menu::orderBy('id', 'desc')->get();

        $pesanan = Pesanan::join(
            'users',
            'pesanan.user_id',
            '=',
            'users.id'
        )->join(
            'menu',
            'pesanan.menu_id',
            '=',
            'menu.id'
        )->select(
            'pesanan.*',
            'users.username',
            'menu.nama_menu'
        )->orderBy('pesanan.id', 'desc')->get();

        return view('admin.dashboard', compact('menus', 'pesanan'));
    }

    public function tambahMenu(Request $request)
    {
        $data = $request->validate([
            'nama_menu' => 'required|string|max:100',
            'harga' => 'required|integer|min:1',
            'kategori' => 'required|string|max:50',
        ]);

        Menu::create($data);

        return redirect()->route('admin.dashboard')
            ->with('sukses', 'Menu berhasil ditambahkan.');
    }

    public function editMenu(Request $request, $id)
    {
        $data = $request->validate([
            'nama_menu' => 'required|string|max:100',
            'harga' => 'required|integer|min:1',
            'kategori' => 'required|string|max:50',
        ]);

        Menu::findOrFail($id)->update($data);

        return redirect()->route('admin.dashboard')
            ->with('sukses', 'Menu berhasil diperbarui.');
    }

    public function hapusMenu($id)
    {
        $menu = Menu::findOrFail($id);

        if (Pesanan::where('menu_id', $id)->exists()) {
            return back()->with(
                'gagal',
                'Menu memiliki riwayat pesanan sehingga tidak dapat dihapus.'
            );
        }

        $menu->delete();

        return back()->with('sukses', 'Menu berhasil dihapus.');
    }

    public function ubahStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => 'required|in:Menunggu Konfirmasi,Sedang Dimasak,Siap Diambil,Selesai,Lunas',
        ]);

        Pesanan::findOrFail($id)->update($data);

        return back()->with('sukses', 'Status pesanan berhasil diubah.');
    }
}